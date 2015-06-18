<?php

namespace Gheb\Bundle\DoctrineCrossBundleMappingBundle\Traits;

use Doctrine\Common\Inflector\Inflector;

/**
 * Provide a way to compensate the lack of methods like addAttribute, removeAttribute, isAttribute, getAttribute and setAttribute.
 *
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
trait CrossBundleMappingTrait
{
    public function __call($name, $arguments)
    {
        switch(true){
            case 0 === strpos($name, 'get'):
                $attr = lcfirst(substr($name, 3));
                return !isset($this->$attr) ? null : $this->$attr;
            case 0 === strpos($name, 'is'):
                $attr = lcfirst(substr($name, 2));
                return !isset($this->$attr) ? false : $this->$attr === true;
            case 0 === strpos($name, 'set'):
                $attr = lcfirst(substr($name, 3));
                $value = current($arguments);
                $this->$attr = $value;
                break;
            case 0 === strpos($name, 'add'):
                $attr = Inflector::pluralize(lcfirst(substr($name, 3)));
                $element = current($arguments);
                if (is_array($this->$attr)){
                    array_push($this->$attr,$element);
                } elseif ($this->$attr instanceof \Traversable){
                    if (!$this->$attr->contains($element)) {
                        $this->$attr->add($element);
                    }
                }
                break;
            case 0 === strpos($name, 'remove'):
                $attr = Inflector::pluralize(lcfirst(substr($name, 6)));
                $element = current($arguments);

                if (is_array($this->$attr)){
                    $key = array_search($element, $this->$attr);
                    if(false !== $key) {
                        $ar = $this->$attr;unset($ar[$key]);$this->$attr = $ar;
                    }
                } elseif ($this->$attr instanceof \Traversable){
                    if ($this->$attr->contains($element)) {
                        $this->$attr->removeElement($element);
                    }
                }

                break;
            default:
                return null;
        }

        return $this;
    }
}
