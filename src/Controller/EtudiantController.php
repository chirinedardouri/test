<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EtudiantRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Etudiant;
use App\Form\EtudiantType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant')]
    public function index(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }
    #[Route('/Liste', name: 'app_listeE')]
    public function liste(EtudiantRepository $repository): Response
    {
        $etudiant=$repository->findAll(); 
        return $this->render('/etudiant/listeEtudiant.html.twig', ['etudiants' => $etudiant]);
    }
    #[Route('/addE', name: 'app_Add')]
    public function Add(Request $request, EntityManagerInterface $em): Response
    {
        $etudiant= new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid() ) {
           
          
            $em->persist($etudiant);
            $em->flush();
    
            
            $this->addFlash('success', 'a été ajouté avec succès !');
    
            return $this->redirectToRoute('app_listeE');
        }
       
    
        return $this->render('classe/Add.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    
    
    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(EtudiantRepository $repository, $id, Request $request, EntityManagerInterface $em): Response
    {
        $etudiant = $repository->find($id);
        if (!$etudiant) {
            throw $this->createNotFoundException('classe non trouvé');
        }
    
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->add('Edit', SubmitType::class);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            // Message flash de succès
            $this->addFlash('success', 'La classe est modifié avec succes !');
    
            return $this->redirectToRoute("app_listeE");
        }
    
        return $this->render('etudiant/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/deleteClasse/{id}', name: 'app_delete')]
    public function delete($id, EtudiantRepository $repository, EntityManagerInterface $em): Response
    {
        $etudiant= $repository->find($id);
    
        if (!$etudiant) {
            throw $this->createNotFoundException(' non trouvé');
        }
    
        $em->remove($etudiant);
        $em->flush();
    
        return $this->redirectToRoute('app_listeE');
    }
    

}
