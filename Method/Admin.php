<?php
namespace GDO\Guestbook\Method;

use GDO\UI\GDT_Page;
use GDO\UI\MethodPage;
use GDO\Core\Application;
use GDO\Core\MethodAdmin;
use GDO\Guestbook\Module_Guestbook;

final class Admin extends MethodPage
{
    use MethodAdmin;
    
    public function beforeExecute()
    {
        if (Application::instance()->isHTML())
        {
            $this->renderNavBar();
    
            $mod = Module_Guestbook::instance();
            GDT_Page::$INSTANCE->topTabs->addField($mod->adminBar());
        }
    }
    
}
