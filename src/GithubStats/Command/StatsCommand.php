<?php
namespace GithubStats\Command;

use Cilex\Command\Command;
use Github\HttpClient\CachedHttpClient;
use Oregon\Oregon;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Github\Client as GithubApi;
use Packagist\Api\Client as PackagistApi;
use YaLinqo\Enumerable;

/**
 * Class StatsCommand
 * @package GithubStats\Command
 */
class StatsCommand extends Command {

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $this->output = $output;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('compile')
            ->setDescription('Compiles the statistics for an organisation')
            ->addArgument('organisation', InputArgument::REQUIRED, 'The name of the organisation');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $organisation = $input->getArgument("organisation");
        $statistics = $this->getStatistics($organisation);
        $output->writeln("<info>Contributions:</info>     " . $statistics["contributions"]);
        $output->writeln("<info>Contributors:</info>      " . $statistics["contributors"]);
        $output->writeln("<info>Forks:</info>             " . $statistics["forks"]);
        $output->writeln("<info>Watchers:</info>          " . $statistics["watchers"]);
        $output->writeln("<info>Downloads total:</info>   " . $statistics["downloadstotal"]);
        $output->writeln("<info>Downloads monthly:</info> " . $statistics["downloadsmonthly"]);
    }

    /**
     * @param $organisation
     * @return array
     */
    private function getStatistics($organisation)
    {
        $httpClient = new CachedHttpClient();
        $githubClient = new GithubAPI($httpClient);
        $packagistClient = new PackagistApi();
        $oregon = new Oregon($organisation, $githubClient, $packagistClient);

        $contributors = $oregon->getContributors();
        $repositories = $githubClient->api('organization')->repositories($organisation);

        $downloads = $this->oregon->getDownloads();

        $statistics = array(
            'contributions'    => Enumerable::from($contributors)->sum('$v["contributions"]'),
            'contributors'     => count($contributors),
            'forks'            => Enumerable::from($repositories)->sum('$v["forks_count"]'),
            'watchers'         => Enumerable::from($repositories)->sum('$v["watchers_count"]'),
            'downloadstotal'   => $downloads->getTotal(),
            'downloadsmonthly' => $downloads->getMonthly(),
        );

        return $statistics;
    }

}
