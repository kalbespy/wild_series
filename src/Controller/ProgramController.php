<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\User;
use App\Form\ProgramType;
use App\Form\SearchProgramType;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Service\ProgramDuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', methods: ['GET', 'POST'], name: 'index')]
    public function index(Request $request, RequestStack $requestStack, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(SearchProgramType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs = $programRepository->findLikeName($search);
        } else {
            $programs = $programRepository->findAll();
        }

        return $this->renderForm('program/index.html.twig', [
            'programs' => $programs,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        // Create a new Program Object
        $program = new Program();

        // Create the form, linked with $program
        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);

            // Set the program's owner
            $program->setOwner($this->getUser());

            // Deal with the submitted data
            $programRepository->save($program, true);

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message
            $this->addFlash('success', 'La nouvelle série a bien été ajoutée');

            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }

        // Render the form (best practice)
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        //Check wether the logged in user is the owner of the program
        if ($this->isGranted('ROLE_ADMIN') != true) {
            if ($this->getUser() !== $program->getOwner()) {
                // If not the owner, throws a 403 Access Denied exception
                throw $this->createAccessDeniedException('Only the owner can edit the program!');
            }
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $programRepository->save($program, true);

            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message
            $this->addFlash('success', 'La série a bien été modifiée');

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', methods: ['GET'], name: 'show')]
    public function show(Program $program, ProgramRepository $programRepository, ProgramDuration $programDuration): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program : ' . $program['slug'] . ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program)
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $programRepository->remove($program, true);

            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message
            $this->addFlash('danger', 'La série a bien été supprimée');
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{programSlug}/seasons/{season_id}', methods: ['GET'], name: 'season_show')]
    #[Entity('program', options: ['mapping' => ['programSlug' => 'slug']])]
    #[Entity('season', options: ['mapping' => ['season_id' => 'id']])]
    public function showSeason(Program $program, Season $season, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program['id'] . ' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $season['id'] . ' found in program\'s table.'
            );
        }
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/{programSlug}/season/{season_id}/episode/{episodeSlug}', methods: ['GET'], name: 'episode_show')]
    #[Entity('program', options: ['mapping' => ['programSlug' => 'slug']])]
    #[Entity('season', options: ['mapping' => ['season_id' => 'id']])]
    #[Entity('episode', options: ['mapping' => ['episodeSlug' => 'slug']])]
    public function showEpisode(Program $program, Season $season, Episode $episode, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program['id'] . ' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $season['id'] . ' found in program\'s table.'
            );
        }

        if (!$episode) {
            throw $this->createNotFoundException(
                'No season with id : ' . $episode['id'] . ' found in program\'s table.'
            );
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
