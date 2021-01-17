<?php


namespace App\Service;

use Symfony\Component\Form\FormInterface;

class ErrorService
{
    /**
     * @param FormInterface $form
     * @return array
     */
    public function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childFrom) {
            if ($childFrom instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childFrom)) {
                    $errors[$childFrom->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}