<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    #[Route('/insat/todoliste', name: 'app_to_do')]
    public function index(Request $request): Response
    {

        $session=$request->getSession();
        if (!$session->has('todos')) {
            $todos = array(
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            );
            $session->set('todos',$todos);
        }
        return $this->render('to_do/listeToDo.html.twig');
    }

     #[Route("insat/addTodo/{cle}/{valeur}", name: 'add')]
    public function addToDo(Request $request,$cle,$valeur):Response
    {
        $session=$request->getsession();
       if(!$session->has('todos')){
         $this->addFlash('error',"la liste de ToDo n’est pas encore initialisée");
         return $this->redirectToRoute("app_to_do");
       }
       else {
           $todos = $session->get('todos');
         if(isset($todos[$cle])){
             $this->addFlash('success',"le ToDo $cle est mis à jour avec la valeur $valeur ");
             $todos[$cle]=$valeur;
             $session->set('todos',$todos);

         }
         else{
             $this->addFlash('success',"ToDo $cle  est ajouté  avec succées ");
             $todos[$cle]=$valeur;
             $session->set('todos',$todos);

         }
           return $this->render('to_do/listeToDo.html.twig', [
               'controller_name' => 'ToDoController',
           ]);


           }





    }
    #[Route("insat/deleteTodo/{cle}", name: 'delete')]
    public function deleteToDo(Request $request,$cle):Response
    {
        $session=$request->getsession();
        if(!$session->has('todos')){
            $this->addFlash('error',"la liste de ToDo n’est pas encore initialisée");
            return $this->redirectToRoute("app_to_do");
        }
        else {
            $todos = $session->get('todos');

            if (!isset($todos[$cle])) {
                $this->addFlash('error', "le ToDo $cle que vous voulez supprimer n'existe pas ");

            }
            else{
                $this->addFlash('success', "le ToDo $cle est supprimé avec succées ");
                unset($todos[$cle]);
                $session->set('todos',$todos);

            }
            return $this->render('to_do/listeToDo.html.twig', [
                'controller_name' => 'ToDoController',
            ]);
        }

    }
    #[Route("insat/resetTodo", name: 'reset')]
    public function resetToDo(Request $request):Response
    {
        $session=$request->getsession();

        if(!$session->has('todos')){
            $this->addFlash('error',"la liste de ToDo n’est pas encore initialisée");
            return $this->redirectToRoute("app_to_do");
        }
        else {
            $this->addFlash('success',"ToDos Reset effectué avec succés");
            $todos = $session->get('todos');
            $session->clear();
            $response = $this->forward('App\Controller\ToDoController::index');
            return $response ;
        }

    }

}
