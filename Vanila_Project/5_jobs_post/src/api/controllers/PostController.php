<?php
namespace API\Controllers;

use \API\Core\Controller;
use \DB\Models\Comment;
use \DB\Models\JobPost;
use \DB\Models\JobSeeker;
use \DB\Models\Applicant;

class PostController extends Controller
{
    public function AddNewPost($position, $company, $location, $salary, $industry, $description) {
        $comment = new JobPost($_SESSION['userId'], '-', $position, $industry, $location, $salary, $description);
        return $this->response($comment->getData());
    }
    public function Apply($post_id)
    {
        $applicants = Applicant::getAllApplicantsByJobPostId($post_id);
        if($applicants != null) {
            foreach ($applicants as $applicant) {
                //var_dump($applicant['id']);
                if($applicant['job_seeker_id'] == $_SESSION['userId']) {
                    return $this->response("already applied", false);
                }
            }
        }
        $newApplicant = new Applicant($post_id, $_SESSION['userId'], '   ');
        return $this->response("applied successfully");
    }
    public function GetComments($id)
    {
        $results = [];
        $comments = Comment::getAllCommentsByJobPostId($id);
        if($comments != null) {
            foreach ($comments as $comment) { 
                $results[] = $this->GetCommentAutherInfo($comment);
            }
        }
        return $this->response($results);
    }
    public function AddComment($post_id, $content) {
        $comment = new Comment($post_id, $_SESSION['userId'], $content);
        $comment = $this->GetCommentAutherInfo($comment->getData());
        return $this->response($comment);
    }
    private function GetCommentAutherInfo($comment) {
        $auther = JobSeeker::getJobSeekerById($comment['author_id']);
        $auther = $auther->getData();
        $comment['auther'] = $auther;
        return $comment;
    }
}