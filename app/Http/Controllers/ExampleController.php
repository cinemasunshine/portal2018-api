<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Doctrine\Entities\Theater;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Example controller
 *
 * @todo delete
 */
class ExampleController extends Controller
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * constract
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function __invoke(\Illuminate\Http\Request $request)
    {
        /** @var Theater $result */
        $result = $this->em->find(Theater::class, 1);
        dump($result);
    }
}
