<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Astound\Bundle\WebBundle\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\WebBundle\Event\MenuBuilderEvent;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Bundle\WebBundle\Menu\BackendMenuBuilder;

/**
 * Main menu builder.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class AstoundBackendMenuBuilder extends BackendMenuBuilder
{
    /**
     * Builds backend main menu.
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav navbar-right'
            )
        ));

        $childOptions = array(
            'attributes'         => array('class' => 'dropdown'),
            'childrenAttributes' => array('class' => 'dropdown-menu'),
            'labelAttributes'    => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'href' => '#')
        );

        $menu->addChild('dashboard', array(
            'route' => 'sylius_backend_dashboard',
            'labelAttributes' => array('icon' => 'icon-dashboard'),
        ))->setLabel($this->translate('sylius.backend.menu.main.dashboard'));

        $this->addFrontCounterMenu($menu, $childOptions, 'main');
        $this->addProductionMenu($menu, $childOptions, 'main');
        $this->addAssortmentMenu($menu, $childOptions, 'main');
        $this->addSalesMenu($menu, $childOptions, 'main');
        $this->addConfigurationMenu($menu, $childOptions, 'main');
        $this->addContentMenu($menu, $childOptions, 'main');
        $this->addCustomersMenu($menu, $childOptions, 'main');

        $menu->addChild('homepage', array(
            'route' => 'sylius_homepage'
        ))->setLabel($this->translate('sylius.backend.menu.main.homepage'));

        $menu->addChild('logout', array(
            'route' => 'fos_user_security_logout'
        ))->setLabel($this->translate('sylius.backend.logout'));

        return $menu;
    }

    /**
     * Builds backend sidebar menu.
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createSidebarMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav'
            )
        ));

        $childOptions = array(
            'childrenAttributes' => array('class' => 'nav'),
            'labelAttributes'    => array('class' => 'nav-header')
        );

        $this->addFrontCounterMenu($menu, $childOptions, 'sidebar');
        $this->addProductionMenu($menu, $childOptions, 'sidebar');
        
        $this->addSalesMenu($menu, $childOptions, 'sidebar');
        $this->addContentMenu($menu, $childOptions, 'sidebar');
        $this->addAssortmentMenu($menu, $childOptions, 'sidebar');
        $this->addCustomersMenu($menu, $childOptions, 'sidebar');
        $this->addConfigurationMenu($menu, $childOptions, 'sidebar');

        $this->eventDispatcher->dispatch(MenuBuilderEvent::BACKEND_SIDEBAR, new MenuBuilderEvent($this->factory, $menu));

        return $menu;
    }

    /**
     * Add production menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     * @param string        $section
     */
    protected function addProductionMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('production', $childOptions)
            ->setLabel("Production")
        ;

        $child->addChild('tasks', array(
            'route' => 'sylius_backend_taxonomy_index', //'astound_backend_tasks_index',
            'labelAttributes' => array('icon' => 'glyphicon glyphicon-check'),
        ))->setLabel("Tasks");

        $child->addChild('report', array(
            'route' => 'sylius_backend_taxonomy_index',// 'astound_backend_production_index',
            'labelAttributes' => array('icon' => 'glyphicon glyphicon-signal'),
        ))->setLabel("Report");

        $child->addChild('calendar', array(
            'route' => 'sylius_backend_taxonomy_index',// 'astound_backend_calendar_index',
            'labelAttributes' => array('icon' => 'glyphicon glyphicon-calendar'),
        ))->setLabel("Calendar");

    }

    /**
     * Add frounCounter menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     * @param string        $section
     */
    protected function addFrontCounterMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('frounCounter', $childOptions)
            ->setLabel("Front Counter")
        ;
        $child->addChild('search', array(
            'route' => 'astound_order-phone-last_search', 
            'labelAttributes' => array('icon' => 'glyphicon glyphicon-search'),
        ))->setLabel("Search");

        $child->addChild('tasks', array(
            'route' => 'sylius_backend_order_create', 
            'labelAttributes' => array('icon' => 'glyphicon glyphicon-plus'),
        ))->setLabel("Create Order");

    }

    /**
     * Add assortment menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     * @param string        $section
     */
    protected function addAssortmentMenu(ItemInterface $menu, array $childOptions, $section)
    {
        BackendMenuBuilder::addAssortmentMenu($menu,$childOptions, $section);
        // $child = $menu
        //     ->addChild('assortment', $childOptions)
        //     ->setLabel("Assortment")
        // ;

        // $child->addChild('taxonomies', array(
        //     'route' => 'sylius_backend_taxonomy_index',
        //     'labelAttributes' => array('icon' => 'icon-tags'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.taxonomies', $section)));

        // $child->addChild('products', array(
        //     'route' => 'sylius_backend_product_index',
        //     'labelAttributes' => array('icon' => 'icon-folder-open'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.products', $section)));

        // $child->addChild('inventory', array(
        //     'route' => 'sylius_backend_inventory_index',
        //     'labelAttributes' => array('icon' => 'icon-tasks'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.stockables', $section)));

        // $child->addChild('options', array(
        //     'route' => 'sylius_backend_option_index',
        //     'labelAttributes' => array('icon' => 'icon-th'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.options', $section)));

        // $child->addChild('properties', array(
        //     'route' => 'sylius_backend_property_index',
        //     'labelAttributes' => array('icon' => 'icon-cog'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.properties', $section)));

        // $child->addChild('prototypes', array(
        //     'route' => 'sylius_backend_prototype_index',
        //     'labelAttributes' => array('icon' => 'icon-cogs'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.prototypes', $section)));      
    }

    /**
     * Add sales menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     * @param string        $section
     */
    protected function addSalesMenu(ItemInterface $menu, array $childOptions, $section)
    {
        BackendMenuBuilder::addSalesMenu($menu,$childOptions, $section);
        // $child = $menu
        //     ->addChild('sales', $childOptions)
        //     ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.sales', $section)))
        // ;

        // $child->addChild('orders', array(
        //     'route' => 'sylius_backend_order_index',
        //     'labelAttributes' => array('icon' => 'icon-shopping-cart'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.orders', $section)));
        // $child->addChild('new_order', array(
        //     'route' => 'sylius_backend_order_create',
        //     'labelAttributes' => array('icon' => 'icon-plus-sign'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.new_order', $section)));
        // $child->addChild('payments', array(
        //     'route' => 'sylius_backend_payment_index',
        //     'labelAttributes' => array('icon' => 'icon-btc'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.payments', $section)));

        // $child->addChild('promotions', array(
        //     'route' => 'sylius_backend_promotion_index',
        //     'labelAttributes' => array('icon' => 'icon-bullhorn'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.promotions', $section)));
        // $child->addChild('new_promotion', array(
        //     'route' => 'sylius_backend_promotion_create',
        //     'labelAttributes' => array('icon' => 'icon-plus-sign'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.new_promotion', $section)));
    }

    /**
     * Add customers menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     * @param string        $section
     */
    protected function addCustomersMenu(ItemInterface $menu, array $childOptions, $section)
    {
        BackendMenuBuilder::addCustomersMenu($menu,$childOptions, $section);
        // $child = $menu
        //     ->addChild('customer', $childOptions)
        //     ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.customer', $section)))
        // ;

        // $child->addChild('users', array(
        //     'route' => 'sylius_backend_user_index',
        //     'labelAttributes' => array('icon' => 'icon-user'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.users', $section)));
        // $child->addChild('groups', array(
        //     'route' => 'sylius_backend_group_index',
        //     'labelAttributes' => array('icon' => 'icon-group'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.groups', $section)));
    }

    /**
     * Add configuration menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     * @param string        $section
     */
    protected function addConfigurationMenu(ItemInterface $menu, array $childOptions, $section)
    {
        BackendMenuBuilder::addConfigurationMenu($menu,$childOptions, $section);
        // $child = $menu
        //     ->addChild('configuration', $childOptions)
        //     ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.configuration', $section)))
        // ;

        // $child->addChild('general_settings', array(
        //     'route' => 'sylius_backend_general_settings',
        //     'labelAttributes' => array('icon' => 'icon-cog'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.general_settings', $section)));

        // $child->addChild('payment_methods', array(
        //     'route' => 'sylius_backend_payment_method_index',
        //     'labelAttributes' => array('icon' => 'icon-credit-card'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.payment_methods', $section)));

        // $child->addChild('exchange_rates', array(
        //     'route' => 'sylius_backend_exchange_rate_index',
        //     'labelAttributes' => array('icon' => 'icon-money'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.exchange_rates', $section)));

        // $child->addChild('taxation_settings', array(
        //     'route' => 'sylius_backend_taxation_settings',
        //     'labelAttributes' => array('icon' => 'icon-cog'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.taxation_settings', $section)));

        // $child->addChild('tax_categories', array(
        //     'route' => 'sylius_backend_tax_category_index',
        //     'labelAttributes' => array('icon' => 'icon-book'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.tax_categories', $section)));

        // $child->addChild('tax_rates', array(
        //     'route' => 'sylius_backend_tax_rate_index',
        //     'labelAttributes' => array('icon' => 'icon-adjust'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.tax_rates', $section)));

        // $child->addChild('shipping_categories', array(
        //     'route' => 'sylius_backend_shipping_category_index',
        //     'labelAttributes' => array('icon' => 'icon-book'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.shipping_categories', $section)));

        // $child->addChild('shipping_methods', array(
        //     'route' => 'sylius_backend_shipping_method_index',
        //     'labelAttributes' => array('icon' => 'icon-truck'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.shipping_methods', $section)));

        // $child->addChild('shipments', array(
        //     'route' => 'sylius_backend_shipment_index',
        //     'labelAttributes' => array('icon' => 'icon-plane'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.shipments', $section)));

        // $child->addChild('countries', array(
        //     'route' => 'sylius_backend_country_index',
        //     'labelAttributes' => array('icon' => 'icon-flag'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.countries', $section)));

        // $child->addChild('zones', array(
        //     'route' => 'sylius_backend_zone_index',
        //     'labelAttributes' => array('icon' => 'icon-globe'),
        // ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.zones', $section)));
    }
}
