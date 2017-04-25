<?php declare(strict_types=1);
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Zend\Form\Element;

use Zend\Form\Element;
use Zend\Form\ElementPrepareAwareInterface;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputProviderInterface;
use Zend\InputFilter\FileInput;

class File extends Element implements InputProviderInterface, ElementPrepareAwareInterface
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'file',
    ];

    /**
     * Prepare the form element (mostly used for rendering purposes)
     *
     * @param  FormInterface $form
     */
    public function prepareElement(FormInterface $form): void
    {
        // Ensure the form is using correct enctype
        $form->setAttribute('enctype', 'multipart/form-data');
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInput()}.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'type'     => FileInput::class,
            'name'     => $this->getName(),
            'required' => false,
        ];
    }
}
