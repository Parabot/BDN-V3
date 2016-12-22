<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Signatures\Types;

use Doctrine\ORM\Mapping as ORM;
use Parabot\BDN\BotBundle\Entity\Signatures\AbstractSignature;

class ImageSignatureType extends AbstractSignature {

    public function __construct() {
        parent::__construct('image');
    }

    /**
     * @return string[]
     */
    public function getAllowedExtensions() {
        return [ 'jpg', 'png' ];
    }

    /**
     * @return bool
     */
    public function isAllowedFile() {
        $image = getimagesize($this->getAbsoluteFilePath());

        return $image !== null && $image !== false && is_array($image);
    }
}