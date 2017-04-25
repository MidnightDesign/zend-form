<?php declare(strict_types=1);
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Form\TestAsset;

use Zend\Form\Form;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;

class NewProductForm extends Form
{
    public function __construct()
    {
        parent::__construct('create_product');

        $this->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator())
             ->setInputFilter(new InputFilter());

        $fieldset = new ProductFieldset();
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit'
            ]
        ]);
    }
}
