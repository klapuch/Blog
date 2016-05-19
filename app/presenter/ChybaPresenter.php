<?php
declare(strict_types = 1);
namespace Facedown\Presenter;

use Nette;
use Tracy\ILogger;


class ChybaPresenter extends Nette\Object implements Nette\Application\IPresenter
{
	/** @var ILogger */
	private $logger;


	public function __construct(ILogger $logger)
	{
		$this->logger = $logger;
	}


	/**
	 * @return Nette\Application\IResponse
	 */
	public function run(Nette\Application\Request $request)
	{
		$e = $request->getParameter('exception');

		if ($e instanceof Nette\Application\BadRequestException) {
			// $this->logger->log("HTTP code {$e->getCode()}: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}", 'access');
			return new Nette\Application\Responses\ForwardResponse($request->setPresenterName('Chyba4xx'));
		}

		$this->logger->log($e, ILogger::EXCEPTION);
		return new Nette\Application\Responses\CallbackResponse(function () {
			require __DIR__ . '/templates/Chyba/500.phtml';
		});
	}

}
