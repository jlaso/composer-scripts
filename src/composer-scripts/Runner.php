<?php

namespace JLaso\ComposerScripts;

use Composer\IO\IOInterface;
use Composer\Script\Event;
use Symfony\Component\Yaml\Yaml;

class Runner
{
    // currently supported operations
    protected static $supportedTypes = [
        'copy',
    ];

    /**
     * @var string
     */
    protected static $rootFolder;

    /**
     * @param Event $event
     */
    public static function execute(Event $event)
    {
        self::$rootFolder = dirname($event->getComposer()->getConfig()->get('vendor-dir'));
        $extras = $event->getComposer()->getPackage()->getExtra();
        if(!isset($extras['jlaso-composer-scripts'])){
            return;
        }
        $configuration = $extras['jlaso-composer-scripts'];
        if(!is_file($configuration)){
            throw new \InvalidArgumentException('The parameter points to '.$configuration.', but the file doesn\'t exist');
        }
        $scripts = Yaml::parse(file_get_contents($configuration));
        if(!isset($scripts['scripts'])){
            throw new \InvalidArgumentException('The file '.$configuration.', must contain a scripts root key');
        }
        $io = $event->getIO();

        foreach($scripts['scripts'] as $scriptType=>$scriptSequence){
            if(!in_array($scriptType, self::$supportedTypes)){
                throw new \InvalidArgumentException('script type '.$scriptType.' not supported!');
            }
            foreach ($scriptSequence as $scriptStep) {
                if(self::$scriptType($scriptStep, $io)){

                };
            }
        }
    }

    /**
     * @param mixed $data
     * @param IOInterface $io
     */
    protected static function copy($data, IOInterface $io)
    {
        $source = self::$rootFolder.DIRECTORY_SEPARATOR.self::doPath($data['source']);
        $dest = self::doPath($data['dest']);
        if(is_file($source) && is_dir($dest)) {
            $dest .= DIRECTORY_SEPARATOR.basename($source);
        }
        $cmd = isset($data['method']) && ('ln' === strtolower($data['method'])) ? 'ln' : 'cp';
        $io->write(sprintf("\t%s -> <info>%s</info> -> %s", $data['source'], $cmd, $dest));
        file_exists($dest) && unlink($dest);
        if('cp' != $cmd){
            symlink($source, $dest);
        }else{
            copy($source, $dest);
        }
    }

    /**
     * @param string $path
     * @return string
     */
    protected static function doPath($path)
    {
        // @TODO: point to bundles with @NameBundle instead to use the whole path
        //if(preg_match("/^@(?<bundle>\w+)Bundle/", $path, $match)){
        //    $files = glob('src');
        //    var_dump($files); die;
        //}
        return $path;
    }
}