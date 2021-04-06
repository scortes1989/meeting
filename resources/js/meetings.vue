<template>
    <div class="container">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dlgCreateMeeting">
            Crear
        </button>

        <table class="table table-bordered table-condensed mt-5">
            <thead>
                <th>Nº</th>
                <th>Título</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Reunión</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                <tr v-for="meeting in meetings.data">
                    <td>{{ meeting.number }}</td>
                    <td>{{ meeting.name }}</td>
                    <td>{{ meeting.created_at }}</td>
                    <td>{{ meeting.date }}</td>
                    <td>
                        <button @click="show(meeting)" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dlgEditMeeting">
                            Editar
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" id="dlgCreateMeeting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="#" @submit.prevent="store">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Nueva Reunión</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"/>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label></label>
                                        <input type="text" placeholder="Título de la Reunión" class="form-control" v-model="create.name">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label></label>
                                        <input type="date" placeholder="Fecha" class="form-control" v-model="create.date">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label></label>
                                        <textarea class="form-control" placeholder="Descripción de la Reunión"
                                                  v-model="create.description"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="dlgEditMeeting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="#" @submit.prevent="update">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Actualizar Reunión</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"/>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label></label>
                                        <input type="text" placeholder="Título de la Reunión" class="form-control" v-model="edit.name">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label></label>
                                        <textarea class="form-control" placeholder="Descripción de la Reunión"
                                                  v-model="edit.description"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                meetings: [],
                create: {},
                edit: {},
            }
        },

        created() {
            this.index();
        },

        methods: {
            async index() {
                await axios.get('/api/v1/meetings')
                    .then((data) => {
                        this.meetings = data.data;
                    });
            },

            async store() {
                await axios.post('/api/v1/meetings', this.create)
                    .then(() => {
                        this.index();
                    });
            },

            show(element) {
                this.edit = _.clone(element);
            },

            async update() {
                await axios.put('/api/v1/meetings/' + this.edit.id, this.edit)
                    .then(() => {
                        this.index();
                    });
            },

            destroy() {
                // eliminar
            }
        }
    }
</script>