<a href="{{ url('/medic/appointments') }}" class="btn btn-primary {{ set_active('medic/appointments') }}">Agenda del dia</a>
<a href="{{ url('/medic/appointments/calendar') }}" class="btn btn-success {{ set_active('medic/appointments/calendar') }}">Calendario Semanal</a>
<a href="{{ url('/medic/patients/create') }}" class="btn btn-danger {{ set_active('medic/patients/create') }}">Nuevo Paciente</a>
<a href="{{ url('/medic/appointments/create?create=1') }}" class="btn btn-info {{ set_active('medic/appointments/create') }}">Crear Consulta</a>