<?php
namespace App\Transformers;
use App\Activity;
use League\Fractal\TransformerAbstract;
class ActivityTransformer extends TransformerAbstract
{
    public function transform(Activity $activity)
    {
        return [
            "description" => call_user_func_array([$this, $activity->name], [$activity]),
            "lapse" => $activity->created_at->diffForHumans(),
            "user" => $activity->user,
        ];
    }
    protected function created_task(Activity $activity)
    {
        return $activity->user->name . " created a task, " . $activity->subject->title;
    }
    protected function created_comment(Activity $activity)
    {
        return $activity->user->name . " left a comment on the task, " . $activity->subject->task->title;
    }
    protected function created_serie(Activity $activity)
    {
        return "<b>".$activity->user->name . "</b> a crée un nouveau projet, " . $activity->subject->titre;
    }
    protected function updated_serie(Activity $activity)
    {
        return "<b>".$activity->user->name . "</b> a modifié " . $activity->subject->titre;
    }
    protected function deleted_serie(Activity $activity)
    {
        return "<b>".$activity->user->name . "</b> a supprimé un projet ";
    }
    protected function created_saisons(Activity $activity)
    {
        return $activity->user->name . " a crée un(e) " . $activity->subject->type ." " .$activity->subject->numero .": " .$activity->subject->name;
    }
    protected function updated_saisons(Activity $activity)
    {
        return $activity->user->name . " a modifié " . $activity->subject->type ." " .$activity->subject->numero .": " .$activity->subject->name;
    }
    protected function deleted_saisons(Activity $activity)
    {
        return $activity->user->name . " a supprimé une section ";
    }
    protected function created_genres(Activity $activity)
    {
        return "<b>".$activity->user->name . "</b> a ajouté le genre  <b>" . $activity->subject->name."</b>";
    }
    protected function updated_genres(Activity $activity)
    {
        return "<b>".$activity->user->name . "</b> a modifié le genre <b>" . $activity->subject->name."</b>";
    }
    protected function deleted_genres(Activity $activity)
    {
        return "<b>".$activity->user->name . "</b> a retiré un genre";
    }
}