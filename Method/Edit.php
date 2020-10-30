<?php
namespace GDO\Guestbook\Method;

use GDO\Form\MethodCrud;
use GDO\Guestbook\GDO_GuestbookMessage;

final class Edit extends MethodCrud
{
    public function hrefList()
    {
        return href('Guestbook', 'View', "id={$this->gdo->getID()}");
    }

    public function gdoTable()
    {
        return GDO_GuestbookMessage::table();
    }

    
}
