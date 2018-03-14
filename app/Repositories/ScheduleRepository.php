<?php

namespace App\Repositories;

use App\Schedule;
use App\User;

class ScheduleRepository extends DbRepository
{
    /**
     * Construct
     * @param User $model
     */
    public function __construct(Schedule $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }

    /**
     * save a appointment
     * @param $data
     */
    public function store($data)
    {
        $data = $this->prepareData($data);

        $schedule = $this->model->create($data);

        $schedule = auth()->user()->schedules()->save($schedule);

        // $schedule = $this->model->create($data);

        return $schedule;
    }

    /**
     * Update a appointment
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function update($id, $data)
    {
        $schedule = $this->model->find($id);
        //$data = $this->prepareData($data);
        if (!$schedule) {
            return '';
        }

        $schedule->fill($data);
        $schedule->save();

        return $schedule;
    }

    /**
     * Delete a appointment
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function delete($id)
    {
        $schedule = $this->model->find($id);

        if (!$schedule) {
            return -1;
        }

        $schedule = $schedule->delete();

        return $schedule;
    }

    /**
     * Find all the appointments by Doctor
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByDoctor($id, $search = null, $limit = 5)
    {
        $order = 'date';
        $dir = 'desc';

        $schedules = $this->model->where('user_id', $id);

        if (!$search) {
            return $schedules->with('user', 'office')->orderBy('schedules.' . $order, $dir)->paginate($limit);
        }

        if (isset($search['q']) && trim($search['q'] != '')) {
            $schedules = $schedules->Search($search['q']);
        }

        if (isset($search['date']) && $search['date'] != '') {
            $schedules = $schedules->whereDate('date', $search['date']);
        }

        if (isset($search['order']) && $search['order'] != '') {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != '') {
            $dir = $search['dir'];
        }

        return $schedules->with('user', 'office')->orderBy('schedules.' . $order, $dir)->paginate($limit);
    }

    /**
     * Find all the appointments by Doctor
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByDoctorWithoutPagination($id, $search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        $schedules = $this->model->has('office')->where('user_id', $id);

        if (!$search) {
            return $schedules->with('office', 'user')->get();
        }

        if (isset($search['q']) && trim($search['q'])) {
            $schedules = $schedules->Search($search['q']);
        }
        if (isset($search['office']) && $search['office'] != '') {
            $schedules = $schedules->where('office_id', $search['office']);
        }

        if (isset($search['date1']) && $search['date1'] != '') {
            // dd($search['date2']);

            $date1 = $search['date1'];
            $date2 = (isset($search['date2']) && $search['date2'] != '') ? $search['date2'] : $search['date1'];
            $date2 = $date2;

            $schedules = $schedules->where([['schedules.date', '>=', $date1],
                    ['schedules.date', '<=', $date2->endOfDay()]]);
        }

        if (isset($search['order']) && $search['order'] != '') {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != '') {
            $dir = $search['dir'];
        }

        return $schedules->with('office', 'user.settings')->orderBy('schedules.' . $order, $dir)->get();
    }

    /*   public function findById($id)
     {
         return $this->model->with('diseaseNotes')->findOrFail($id);
     }*/

    private function prepareData($data)
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
