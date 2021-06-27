<template>
  <div class="container mt-3">
    <div>
      <h2 class="text-muted d-inline-block">Available movies</h2>
      <b-button class="float-right border-0" variant="success" v-b-modal.crud-modal @click.stop.prevent="onMovieClick(null)">
        <i class="fa fa-plus-circle"></i> New Movie
      </b-button>
    </div>
    <hr>
    <div v-if="movies.length !== 0">
      <div class="ml-2 highlight-row mt-1 p-2" v-for="movie in movies" :key="movie.id" v-b-modal.crud-modal
           @click="onMovieClick(movie)">
        <div class="row border-grey-bottom">
          <div class="col-11">
            <a href="#" class="d-inline-block" @click.stop.prevent>
              <span class="font-weight-bold">
                {{movie.name}} ({{movie.year}})
              </span>
            </a>
            <p class="ml-2 text-muted m-0 text-small"><i class="fa fa-clock"></i> {{movie.updated_at || movie.created_at}}</p>
          </div>
          <div class="col mr-1">
            <div class="float-right">
              <a href="#" @click.stop.prevent="onDeleteClick(movie)">
                <i class="fa fa-trash"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="text-primary">
      <i class="fa fa-exclamation-triangle"></i> It seems like there are no movies! Add one by pressing 'New Movie'
    </div>

    <ValidationObserver v-slot="{ passes }">
      <b-modal id="crud-modal"
               no-fade @ok.prevent="passes(() => onUpsertClick())" centered>
        <template slot="modal-title">
          Editing movie <span class="text-primary font-weight-bold">{{name || ''}}</span>
        </template>
        <ValidationProvider name="Name of the movie" rules="required" v-slot="{ errors }">
          <label>
            <i class="fa fa-file text-primary"></i> Name of the movie
          </label>
          <b-input size="sm" class="form-control" v-model="name"></b-input>
          <span class="text-danger">{{ errors[0] }}</span>
        </ValidationProvider>
        <hr>
        <ValidationProvider name="Release year" rules="required|numeric" v-slot="{ errors }">
          <label>
            <i class="fa fa-calendar text-primary"></i> Release year
          </label>
          <b-input type="number" size="sm" class="form-control" v-model="year"></b-input>
          <span class="text-danger">{{ errors[0] }}</span>
        </ValidationProvider>
      </b-modal>
    </ValidationObserver>
  </div>
</template>

<script>
import nprogress from 'nprogress';
import toastr from 'toastr';
export default {
  name: "Dashboard",
  data() {
    return {
      movies: [],
      name: null,
      year: null,
      id: null
    }
  },
  mounted() {
    this.getMovies();
  },
  methods: {
    getMovies() {
      nprogress.start();
      fetch(`${process.env.VUE_APP_BACKEND_API_URL}/movies`)
        .then(response => response.json())
        .then(data => {
          this.movies = data;
      }).finally(() => {
        nprogress.done();
      });
    },
    onMovieClick(movie) {
      this.id = movie?.id;
      this.name = movie?.name;
      this.year = movie?.year;
    },
    onUpsertClick() {
      this.$bvModal.hide('crud-modal');
      nprogress.start();
      fetch(`${process.env.VUE_APP_BACKEND_API_URL}/movies/upsert/${this.id ?? ''}`,
          {
            body: JSON.stringify({
              name: this.name,
              year: this.year
            }),
            method: 'post',
            mode: 'cors',
            headers: {
              'Content-Type': 'application/json',
            },
          }).then(response => {
        if (response.status === 422) {
          return response.text().then(data => {
            return Promise.reject({message: data});
          })
        }
        return response.json();
      })
      .then(data => {
        this.movies = data.movies;
        toastr.success(`Movie database successfully updated`);
      })
      .catch(error => {
        toastr.error('Failed to update the movie: ' + error.message);
      })
      .finally(() => {
        nprogress.done();
      })
    },
    onDeleteClick(movie) {
      this.$confirm(
          {
            title: `Deleting "${movie.name}"`,
            message: `Are you sure you want to delete "${movie.name}?"`,
            button: {
              no: 'No',
              yes: 'Yes'
            },
            callback: confirm => {
              if (confirm) {
                nprogress.start();
                fetch(`${process.env.VUE_APP_BACKEND_API_URL}/movies/${movie.id}`, {
                  method: 'delete',
                  mode: 'cors',
                  headers: {
                    'Content-Type': 'application/json',
                  },
                }).then(response => response.json())
                    .then(data => {
                      this.movies = data.movies;
                      toastr.success('Movie deleted successfully');
                    })
                    .catch(error => {
                      toastr.error(`Failed to delete movie: ` + error.message);
                    })
                    .finally(() => {
                      nprogress.done();
                    })
              }
            }
          }
      )
    }
  }
}
</script>

<style scoped>
 .text-small {
   font-size: 11px
 }
 .border-grey-bottom {
   border-bottom: 1px solid #eaeaea
 }
</style>
