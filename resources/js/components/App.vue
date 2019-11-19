<template>
  <div>

    <!-- Seccion de buscar y crear producto -->
    <div class="d-flex justify-content-between align-items-center my-1">
      <div>
        <input type="text"
               placeholder="Buscar producto"
               class="form-control"
               v-model="criterioFiltro"
               @keyup.enter="filtrarProductos">
      </div>


      <button class="btn btn-primary"
              data-toggle="modal"
              data-target="#crear-producto-modal">
        Crear Producto
      </button>
    </div>

    <!-- El componente Vuetable muestra la tabla con los datos -->
    <vuetable ref="vuetable"
      api-url="/api/productos/todos"
      :append-params="urlParams"
      :css="css.table"
      :fields="campos"
      pagination-path=""
      @vuetable:pagination-data="onPaginationData">

      <template slot="actions" slot-scope="props">
        <button class="btn btn-sm btn-primary"
                @click="cambiarEstado(props.rowData.id)">
          Cambiar estado
        </button>
    </template>
    </vuetable>

    <!-- Esta es la paginacion de los datos -->
    <div class="d-flex justify-content-between align-items-center">
      <vuetable-pagination-info ref="paginationInfo"
                                info-template="Mostrando {from} a {to} de {total} productos"
                                no-data-template="No hay datos para mostrar">
      </vuetable-pagination-info>
      <vuetable-pagination ref="pagination"
        @vuetable-pagination:change-page="onChangePage"
        :css="css.pagination">
      </vuetable-pagination>
    </div>


    <!-- Modal para crear producto -->
    <div class="modal fade"
         id="crear-producto-modal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="tituloModall"
         aria-hidden="true">

      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tituloModal">Crear Producto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <form id="crear-producto-form"
                  action="#"
                  method="post">

              <div class="form-group">
                <label for="">Producto</label>
                <input type="text" class="form-control" name="nombre" required>
              </div>

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="">Cantidad</label>
                    <input type="number" class="form-control" name="cantidad" required>
                  </div>
                </div>

                <div class="col">
                  <div class="form-group">
                    <label for="">Estado</label>
                    <select id="estado" name="estado" class="form-control" required>
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="">Bodega</label>
                <select id="bodega_id" name="bodega_id" class="form-control" required>
                  <option v-for="bodega in bodegas"
                          :value="bodega.id">
                    {{ bodega.nombre }}
                  </option>
                </select>
              </div>

              <div class="form-group">
                <label for="">Observaciones</label>
                <textarea name="observaciones"
                          cols="30"
                          rows="4"
                          class="form-control">
                </textarea>
              </div>

            </form>

          </div>
          <div class="modal-footer">
            <button type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal">
              Cancelar
            </button>
            <button type="button"
                    class="btn btn-primary"
                    @click="crearProducto">
              Guardar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

export default {
  name: 'app',

  components: {
    Vuetable,
    VuetablePagination,
    VuetablePaginationInfo,
  },

  data() {
    return {
      urlParams: {},
      criterioFiltro: '',
      bodegas: [],
      campos: [ // esta variable define los campos a mostrar en la tabla
        {
          name: 'nombre',
          titleClass: 'text-center bg-dark text-light',
          title: 'Producto',
        },
        {
          name: 'bodega.nombre',
          titleClass: 'text-center bg-dark text-light',
          title: 'Bodega',
          dataClass: 'text-center',
        },
        {
          name: 'cantidad',
          titleClass: 'text-center bg-dark text-light',
          dataClass: 'text-center',
        },
        {
          name: 'estado',
          dataClass: 'text-center',
          titleClass: 'text-center bg-dark text-light',
          callback: (estado) => {
            switch (estado) {
              case 0:
                return `<span class="bg-danger font-weight-bold text-white px-2 py-1 rounded">Inactivo</span>`;
              case 1:
                return `<span class="bg-success font-weight-bold text-white px-2 py-1 rounded">Activo</span>`;
              default:
                return tipo
            }
          }
        },
        {
          name: '__slot:actions',
          dataClass: 'text-center',
          titleClass: 'text-center bg-dark text-light',
          title: 'Gestión'
        }
      ],
      css: { // esta variable sirve para darle estilos a la tabla
        table: {
          tableClass: 'table table-striped table-bordered table-hovered',
          tableHeaderClass: 'bg-dark text-center text-light',
        },
        pagination: {
          infoClass: 'float-left',
          wrapperClass: 'btn-group',
          activeClass: 'btn-primary',
          disabledClass: 'disabled',
          pageClass: 'btn btn-outline-primary',
          linkClass: 'btn btn-outline-primary',
          icons: {
            first: '',
            prev: '',
            next: '',
            last: '',
          }
        }
      },

    }
  },

  methods: {

    cambiarEstado(idProducto) {

      bootbox.confirm('¿Desea cambiar el estado de este producto?', (confirma) => {
        if (!confirma) return;

        $.ajax({
          url: '/api/productos/cambiar-estado',
          method: 'put',
          data: {
            idProducto,
          },
          success: (response) => {
            bootbox.alert(response.mensaje);
            this.recargarDatos();
          },
          error: (response) => {
            bootbox.alert('ERROR. No se pudo cambiar el estado del registro.');
            console.log('Error al contactar la base de datos', response);
          }
        })

      })

    },

    filtrarProductos() {
      if (this.criterioFiltro) {
        this.urlParams.filtro = this.criterioFiltro;
      } else {
        delete this.urlParams.filtro;
      }

      this.recargarDatos();
    },

    crearProducto() {
      const datos = $('#crear-producto-form').serializeArray();

      $.ajax({
        url: '/api/productos/crear',
        method: 'post',
        data: {
          nombre: datos[0].value,
          cantidad: datos[1].value,
          estado: datos[2].value,
          bodega_id: datos[3].value,
          observaciones: datos[4].value,
        },
        success: (response) => {
          $('#crear-producto-modal').modal('hide');
          bootbox.alert(response.mensaje);
          this.recargarDatos();
          $("form .form-control").val('');
        },
        error: (response) => {
          bootbox.alert('ERROR. No se pudo crear el registro.');
          console.log('Error al contactar la base de datos', response);
        }
      })
    },

    fetchBodegas() {
      $.ajax({
        url: '/api/bodegas/todos',
        method: 'get',
        success: (response) => {
          this.bodegas = response;
        },
        error: (response) => {
          bootbox.alert('ERROR. No se pudo contactar con la base de datos.');
          console.log('Error al contactar la base de datos', response);
        }
      })
    },

    recargarDatos () {
      this.$refs.vuetable.reload()
    },

    onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
      this.$refs.paginationInfo.setPaginationData(paginationData)
    },

    onChangePage (page) {
      this.$refs.vuetable.changePage(page)
    }

  },

  created() {
    this.fetchBodegas();
  }
}
</script>

<style>
.vuetable th#_nombre { width: 40%; }
</style>
