<?php namespace App\Repositories\Backend\Score;

use App\Models\StudentEvalStatus;
use Schema;
use InfyOm\Generator\Common\BaseRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Debugbar;



class StudentEvalStatusRepository extends BaseRepository
{

    /**
    * Configure the Model
    *
    **/
    public function model()
    {
      return 'App\Models\StudentEvalStatus';
    }

	public function search($input)
    {
        $query = StudentEvalStatu::query();

        $columns = Schema::getColumnListing('studentEvalStatuses');
        $attributes = array();

        foreach($columns as $attribute)
        {
            if(isset($input[$attribute]) and !empty($input[$attribute]))
            {
                $query->where($attribute, $input[$attribute]);
                $attributes[$attribute] = $input[$attribute];
            }
            else
            {
                $attributes[$attribute] =  null;
            }
        }

        return [$query->get(), $attributes];
    }

    public function apiFindOrFail($id)
    {
        $model = $this->find($id);

        if(empty($model))
        {
            throw new HttpException(1001, "StudentEvalStatues not found");
        }

        return $model;
    }

    public function apiDeleteOrFail($id)
    {
        $model = $this->find($id);

        if(empty($model))
        {
            throw new HttpException(1001, "StudentEvalStatu not found");
        }

        return $model->delete();
    }
}
