<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Controller;

use App\Entity\Product;
use App\Entity\WarehouseRecord;
use App\Form\Filter\ProductFilterForm;
use App\Form\Processor\ProductFormProcessor;
use App\Form\Processor\WarehouseRecordFormProcessor;
use App\Model\Module;
use App\Repository\ProductRepository;
use App\Repository\WarehouseRecordRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class ProductController extends BaseController
{
    /**
     * @Route("", name="products")
     * @Security("is_granted('LIST_PRODUCTS')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $filters = [];

        $user = $this->getUser();

        if ($user->isSuperadmin()) {
            // Do not filter...
        } else {
            $filters[ProductRepository::FILTER_BY_MANAGER] = $user;
        }

        $query = $this->getProductRepository()->findAllQuery($filters, $page, $pageSize);

        $filterForm = $this->buildFilterForm($request, $query, ProductFilterForm::class);

        $pagination = $this->paginate($query, $page, $pageSize, 'product.name', 'asc');

        return [
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
        ];
    }

    /**
     * @Route("/new", name="product_create")
     * @Security("is_granted('PRODUCT_CREATE')")
     * @Template
     */
    public function createAction(Request $request, ProductFormProcessor $processor)
    {
        $product = new Product();

        $company = $this->getCurrentCompany();

        if ($company) {
            $product->setCompany($company);
        }

        return $this->processForm($request, $processor, $product);
    }

    /**
     * @Route("/{id}/edit", name="product_edit")
     * @Security("is_granted('PRODUCT_EDIT', product)")
     * @Template
     */
    public function editAction(Request $request, ProductFormProcessor $processor, Product $product)
    {
        return $this->processForm($request, $processor, $product);
    }

    /**
     * @Route("/{id}/delete", name="product_delete")
     * @Security("is_granted('PRODUCT_DELETE', product)")
     */
    public function deleteAction(Product $product)
    {
        $this->delete($product);

        $this->addFlash('success', 'flash.product.deleted', ['%product%' => $product]);

        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/{id}/warehouse", name="product_show_warehouse")
     * @Security("is_granted('PRODUCT_SHOW_WAREHOUSE', product)")
     * @Template
     */
    public function showWarehouseAction(Request $request, WarehouseRecordFormProcessor $processor, Product $product)
    {
        if (!$product->isEnabledInWarehouse()) {
            throw $this->createNotFoundException('Product is not enabled in warehouse');
        }

        $filters = [
            WarehouseRecordRepository::FILTER_BY_PRODUCT => $product,
        ];

        $query = $this->getWarehouseRecordRepository()->findAllQuery($filters);

        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $pagination = $this->paginate($query, $page, $pageSize);

        return $this->processWarehouseRecordForm($request, $processor, $pagination, $product);
    }

    /**
     * @Route("/{productId}/warehouse/{recordId}/delete", name="product_delete_warehouse_record")
     * @Security("is_granted('PRODUCT_SHOW_WAREHOUSE', product)")
     * @ParamConverter("product", class="App:Product", options={"id" = "productId"})
     * @ParamConverter("record", class="App:WarehouseRecord", options={"id" = "recordId"})
     */
    public function deleteWarehouseRecordAction(Product $product, WarehouseRecord $record)
    {
        $this->delete($record);

        $this->addFlash('success', 'flash.warehouse_record.deleted');

        return $this->redirectToRoute(
            'product_show_warehouse',
            [
                'id' => $product->getId(),
            ]
        );
    }

    /**
     * @Route("/search", name="product_search")
     */
    public function searchAction(Request $request)
    {
        if (!$this->isModuleEnabled(Module::PRODUCTS_MODULE)) {
            return $this->createJsonProblem('Module not enabled', 400);
        }

        $term = $request->get('term');

        $filters = [];

        $products = $this->getProductRepository()->search($term, $this->getCurrentCompany(), $filters);

        return $this->serialize(
            [
                'products' => $products,
            ]
        );
    }

    /**
     * @Route("/tags/search", name="product_tags_search")
     */
    public function searchTagsAction(Request $request)
    {
        if (!$this->isModuleEnabled(Module::PRODUCTS_MODULE)) {
            return $this->createJsonProblem('Module not enabled', 400);
        }

        $term = $request->get('term');

        $tags = $this->getProductTagRepository()->search($term, $this->getCurrentCompany());

        return $this->serialize(
            [
                'tags' => $tags,
            ]
        );
    }

    private function processForm(Request $request, ProductFormProcessor $processor, Product $product = null)
    {
        $processor->process($request, $product);

        if ($processor->isValid()) {
            $this->addFlash(
                'success',
                $processor->isNew() ? 'flash.product.created' : 'flash.product.updated',
                ['%product%' => $processor->getData()]
            );

            if ($processor->isRedirectingTo(ProductFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('products');
            }

            return $this->redirectToRoute(
                'product_edit',
                [
                    'id' => $processor->getData()->getId(),
                ]
            );
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }

    private function processWarehouseRecordForm(
        Request $request,
        WarehouseRecordFormProcessor $processor,
        PaginationInterface $pagination,
        Product $product
    ) {
        $warehouseRecord = new WarehouseRecord();
        $warehouseRecord->setProduct($product);
        $processor->process($request, $warehouseRecord);

        if ($processor->isValid()) {
            $this->addFlash('success', 'flash.warehouse_record.created');

            return $this->redirectToRoute(
                'product_show_warehouse',
                [
                    'id' => $product->getId(),
                ]
            );
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'product' => $product,
        ];
    }
}
