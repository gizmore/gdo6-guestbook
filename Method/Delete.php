<?php
namespace GDO\Guestbook\Method;

use GDO\Core\Method;
use GDO\Core\Website;
use GDO\DB\GDT_Object;
use GDO\Guestbook\GDO_GuestbookMessage;
use GDO\DB\GDT_Token;
use GDO\Date\Time;
use GDO\User\GDO_User;
use GDO\Core\Application;

/**
 * Delete an entry via token hashcode.
 * 
 * @author gizmore
 * @version 6.10
 * @since 6.10
 * 
 * @see GDT_Token
 * @see GDT_Object
 * @see Method
 */
final class Delete extends Method
{
    public function gdoParameters()
    {
        return array(
            GDT_Object::make('id')->table(GDO_GuestbookMessage::table())->notNull(),
            GDT_Token::make('token')->notNull(),
        );
    }
    
    /**
     * @return GDO_GuestbookMessage
     */
    public function getMessage() { return $this->gdoParameterValue('id'); }
    public function getToken() { return $this->gdoParameterVar('token'); }
    
    public function execute()
    {
        if (!($msg = $this->getMessage()))
        {
            return $this->error('err_gbmsg_not_found');
        }
        
        if ($msg->isDeleted())
        {
            return $this->error('err_already_deleted');
        }
        
        if ($this->getToken() !== $msg->gdoHashcode())
        {
            return $this->error('err_token');
        }
        
        $msg->saveVars(array(
            'gbm_deleted' => Application::$MICROTIME,
            'gbm_deletor' => GDO_User::current()->getID(),
        ));
        
        $href = href('Guestbook', 'ApproveList', '&id='.$msg->getGuestbookID());
        return $this->message('msg_gbmsg_deleted')->addField(Website::redirect($href, 12));
    }
    
}
