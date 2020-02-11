<?php

namespace BeyondCode\Credentials;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use BeyondCode\Credentials\Exceptions\InvalidJSON;

class EditCredentialsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'credentials:edit {environment?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt and edit existing credentials. They will be decrypted after saving.';

    /**
     * The command handler.
     *
     * @param \BeyondCode\Credentials\Credentials $credentials
     *
     * @return void
     * @throws InvalidJSON
     */
    public function handle(Credentials $credentials)
    {
        if (! empty($this->argument('environment'))) {
            if (config('credentials.multiple-environments') === false) {
                return $this->info("You must not provide an environment when config('credentials.multiple-environments') is false");
            }
            $filename = config_path("credentials.{$this->argument('environment')}.php.enc");
        } else {
            if (config('credentials.multiple-environments') === true) {
                return $this->info("You must provide an environment when config('credentials.multiple-environments') is true");
            }
            $filename = config('credentials.file');
        }

        $decrypted = $credentials->load($filename);

        $handle = tmpfile();
        $meta = stream_get_meta_data($handle);

        fwrite($handle, json_encode($decrypted, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT, 512));

        $editor = env('EDITOR', 'vi');

        $process = new Process($editor . ' ' . $meta['uri']);

        $process->setTty(true);
        $process->mustRun();

        $data = json_decode(file_get_contents($meta['uri']), JSON_OBJECT_AS_ARRAY, 512, JSON_THROW_ON_ERROR);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw InvalidJSON::create(json_last_error());
        }

        $credentials->store($data, $filename);

        $this->info('Successfully updated credentials.');
    }
}
