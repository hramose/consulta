<a href="{{ url('/medic/appointments?clinic='.$clinic_id) }}" class="btn btn-primary">Agenda del dia</a>
<a href="{{ url('/medic/appointments/create?clinic='.$clinic_id) }}" class="btn btn-success">Calendario Semanal</a>
<a href="{{ url('/medic/patients/create?clinic='.$clinic_id) }}" class="btn btn-danger">Nuevo Paciente</a>
<a href="{{ url('/medic/appointments/create?clinic='.$clinic_id) }}" class="btn btn-info">Crear Consulta</a>