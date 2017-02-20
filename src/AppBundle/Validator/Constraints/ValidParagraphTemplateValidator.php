<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\ParagraphTemplate;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidParagraphTemplateValidator extends ConstraintValidator
{
    /**
     * @param ParagraphTemplate $paragraphTemplate
     * @param ValidParagraphTemplate $constraint
     */
    public function validate($paragraphTemplate, Constraint $constraint)
    {
        if (!$paragraphTemplate->hasParent() && !$paragraphTemplate->hasCompany()) {
            $this->context->buildViolation('error.empty_company')
                ->addViolation();
        }
    }
}
