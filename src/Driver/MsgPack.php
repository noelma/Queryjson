<?php

/**
 * Queryflatfile
 *
 * @package Queryflatfile
 * @author  Mathieu NOËL <mathieu@soosyze.com>
 * @license https://github.com/soosyze/queryflatfile/blob/master/LICENSE (MIT License)
 */

namespace Queryflatfile\Driver;

/**
 * Manipule des données sérialisées avec l'extension msgpack
 *
 * @see https://msgpack.org/
 *
 * @author  Mathieu NOËL
 */
class MsgPack extends \Queryflatfile\Driver
{
    /**
     * {@inheritDoc}
     */
    public function create($path, $fileName, array $data = [])
    {
        $this->checkExtension();
        $file = $this->getFile($path, $fileName);

        if (!file_exists($path)) {
            mkdir($path, 0775);
        }
        if (!file_exists($file)) {
            $fichier = fopen($file, 'w+');
            fwrite($fichier, msgpack_pack($data));

            return fclose($fichier);
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function read($path, $fileName)
    {
        $file = $this->getFile($path, $fileName);

        $this->isExist($file);
        $this->isRead($file);

        $data = file_get_contents($file);

        return msgpack_unpack($data);
    }

    /**
     * {@inheritDoc}
     */
    public function save($path, $fileName, array $data)
    {
        $file = $this->getFile($path, $fileName);

        $this->isExist($file);
        $this->isWrite($file);

        $fp = fopen($file, 'w');
        fwrite($fp, msgpack_pack($data));

        return fclose($fp);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtension()
    {
        return 'msg';
    }

    /**
     * Déclanche une exception si le l'extension du fichier n'est pas chargée.
     *
     * @codeCoverageIgnore has
     *
     * @throws Exception\Driver\ExtensionNotLoadedException
     */
    private function checkExtension()
    {
        if (!extension_loaded('msgpack')) {
            throw new Exception\Driver\ExtensionNotLoadedException('The json extension is not loaded.');
        }
    }
}
