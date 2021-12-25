<?php

namespace App\IseemoreBundle\Command;

use App\IseemoreBundle\Entity\Video;
use App\IseemoreBundle\Service\Telegram\MessageToCreator;
use App\IseemoreBundle\Service\Telegram\SendMessageException;
use App\IseemoreBundle\Service\YoutubeParser;
use App\IseemoreBundle\Service\YoutubeParserException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ParseYoutubeVideosCommand extends Command
{
    protected static $defaultName = 'app:parse_youtube_videos';

    private $parser;
    private $sender;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, string $name, YoutubeParser $parser, MessageToCreator $sender)
    {
        $this->entityManager = $entityManager;
        $this->parser = $parser;
        $this->sender = $sender;
        parent::__construct($name);
    }


    protected function configure()
    {
        $this->setDescription('Парсим видео с YouTube');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $coordinates = [
            '48.707073, 44.516930', # Волгоград
            //'55.584222, 37.385524', # Москва
            '39.715494, 54.670722', # Рязань
            '51.533557, 46.034257', # Саратов
            '55.796127, 49.106405', # Казань
            '59.220496, 39.891523', # Вологда
            '56.326797, 44.006516', # Нижний Новгород
            '52.730545, 41.471223', # Тамбов
            '55.563721, 42.029550', # Владимир
        ];

        $videoCount = 0;
        try {
            foreach ($coordinates as $coordinate) {
                $videos = $this->parser->parse($coordinate, 300);

                foreach ($videos as $video) {
                    $videoEntity = (new Video())->setUrl($video)->setStatus(true);
                    $this->entityManager->persist($videoEntity);
                    $this->entityManager->flush();
                    $videoCount++;
                }
            }
            $io->success('Парсинг окончен.');
            $this->sender->send('Было собрано ' . $videoCount . ' видео');
        } catch (YoutubeParserException $exception) {
            echo 'Не удалось спарсить видео: ' . $exception->getMessage() . PHP_EOL;
        } catch (SendMessageException $exception) {
            echo 'Не удалось отправить сообщение: ' . $exception->getMessage() . PHP_EOL;
        }

        return Command::SUCCESS;
    }
}
