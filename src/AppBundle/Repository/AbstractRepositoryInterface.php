<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Repository;

interface AbstractRepositoryInterface
{
    function findLast();

    function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX);

    function findOneById($id);

    function findAll(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX);

    function countAll(array $filters = []);

    function save($object);

    function delete($object);
}
