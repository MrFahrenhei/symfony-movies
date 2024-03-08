<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MoviesController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    // #[Route('/movies/{name}', name: 'movies', defaults: ['name' => null], methods: ['GET', 'HEAD'])]
    // public function getSingleMovie(?string $name): JsonResponse
    // {
    //     return $this->json([
    //         'message' => 'Welcome to your cinema!, we got ' . $name,
    //         'path' => 'src/Controller/MoviesController.php',
    //     ]);
    // }
    //    #[Route('/movies', name: 'movies')]
    //    public function index(): Response
    //    {
    //        // findAll () => SELECT * FROM movies;
    //        // find () => SELECT * FROM movies WHERE id = 5;
    //        // findBY () => SELECT * FROM movies ORDER BY id DESC;
    //        // findOneBy() => SELECT * FROM movies WHERE id = 6 AND title = 'Batman' ORDER BY id DESC
    //        // count() => SELECT COUNT(*) FROM movies WHERE id = 1;
    //        $repository = $this->em->getRepository(Movie::class);
    //        $movies = $repository->findAll();
    //        return $this->render('index.html.twig', ['movies' => $movies]);
    //    }
    #[Route('/', name:'home')]
    public function main()
    {
        return $this->redirectToRoute('movies');
    }
    #[Route('/movies', name: 'movies', methods: ['GET'])]
    public function index(): Response
    {
        $repository = $this->em->getRepository(Movie::class);
        $movies = $repository->findAll();
        return $this->render('movies/index.html.twig', [
            'movies' => $movies,
            'current_year' => (new DateTime())->format('Y')
        ]);
    }

    #[Route('/movies/create', name: 'create_movie')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newMovie = $form->getData();
            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newMovie->setImagePath('/uploads/' . $newFileName);
            }
            $this->em->persist($newMovie);
            $this->em->flush();
            return $this->redirectToRoute('movies');
        }
        return $this->render('movies/create.html.twig', [
            'form' => $form->createView(),
            'current_year' => (new DateTime())->format('Y')
        ]);
    }

    #[Route('/movies/edit/{id}', name: 'edit_movie')]
    public function editMovie(int $id, Request $request): Response
    {
        $repository = $this->em->getRepository(Movie::class);
        $movie = $repository->find($id);
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                if ($movie->getImagePath() !== null) {
                    if (!file_exists($this->getParameter('kernel.project_dir') . $movie->getImagePath())) {
                        $this->getParameter('kernel.project_dir') . $movie->getImagePath();
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                        try {
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads',
                                $newFileName
                            );
                        } catch (FileException $e) {
                            return new Response($e->getMessage());
                        }
                        $movie->setImagePath('/uploads/'.$newFileName);
                        $this->em->flush();
                        return $this->redirectToRoute('movies');
                    }
                }

            } else {
                $movie->setTitle($form->get('title')->getData());
                $movie->setReleaseYear($form->get('releaseYear')->getData());
                $movie->setDescription($form->get('description')->getData());
                $this->em->flush();
                return $this->redirectToRoute('movies');
            }
        }
        return $this->render('movies/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
            'current_year' => (new DateTime())->format('Y')
        ]);
    }

    #[Route('/movies/delete/{id}', name: 'delete_movie', methods: ['GET', 'DELETE'])]
    public function deleteMovie(int $id): Response
    {
        $repository = $this->em->getRepository(Movie::class);
        $movie = $repository->find($id);
        $this->em->remove($movie);
        $this->em->flush();
        return $this->redirectToRoute('movies');
    }
    #[Route('/movies/{id}', name: 'show_movie', methods: ['GET'])]
    public function singleMovie(int $id): Response
    {
        $repository = $this->em->getRepository(Movie::class);
        $movie = $repository->find($id);
        return $this->render('movies/show.html.twig', [
            'movie' => $movie,
            'current_year' => (new DateTime())->format('Y')
        ]);
    }
}
//  3:49:00
//npx tailwindcss -i ./assets/styles/app.css -o ./public/build/app.css  --watch
// npm run watch