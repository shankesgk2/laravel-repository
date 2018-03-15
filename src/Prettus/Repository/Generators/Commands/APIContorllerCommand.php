<?php
/**
 * Created by PhpStorm.
 * User: sgk
 * Date: 2018/3/10
 * Time: 下午3:13
 */

namespace Prettus\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Prettus\Repository\Generators\APIControllerGenerator;
use Prettus\Repository\Generators\FileAlreadyExistsException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class APIContorllerCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:apicontroller';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new RESTful API controller.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'API Controller';

    /**
     * ControllerCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the command.
     *
     * @see fire()
     * @return void
     */
    public function handle()
    {
        $this->laravel->call([$this, 'fire'], func_get_args());
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        try {
            // Generate create request for controller
            $this->call('make:request', [
                'name' => $this->argument('name') . 'CreateRequest'
            ]);

            // Generate update request for controller
            $this->call('make:request', [
                'name' => $this->argument('name') . 'UpdateRequest'
            ]);

            (new APIControllerGenerator([
                'name' => $this->argument('name'),
                'force' => $this->option('force'),
                'api' => $this->option('api')
            ]))->run();

            $this->info($this->type . ' created successfully.');

        } catch (FileAlreadyExistsException $e) {
            $this->error($this->type . ' already exists!');

            return false;
        }
    }


    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of model for which the controller is being generated.',
                null
            ],
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ],
            [
                'api',
                'api',
                InputOption::VALUE_OPTIONAL,
                '生成API路径，可选值为 f/b，f=FrontEnd（前端），b=BackEnd（后端），默认前端',
                'f'
            ],
        ];
    }
}