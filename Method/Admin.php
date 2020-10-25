<?php
namespace GDO\Guestbook\Method;

use GDO\UI\MethodPage;
use GDO\Core\MethodAdmin;
use GDO\Guestbook\Module_Guestbook;

final class Admin extends MethodPage
{
    use MethodAdmin;
    
    public function execute()
    {
        $mod = Module_Guestbook::instance();
        return $this->renderNavBar()->
        add($mod->adminBar())->
        add(parent::execute());
    }
    
}
