<?php
namespace GDO\Guestbook\Method;

use GDO\Core\Method;
use GDO\DB\GDT_Token;
use GDO\DB\GDT_Object;
use GDO\Guestbook\GDO_GuestbookMessage;
use GDO\Date\Time;
use GDO\User\GDO_User;
use GDO\Core\Website;

/**
 * Moderate a guestbook entry.
 * @author gizmore
 * @version 6.10
 * @since 6.10
 */
final class Approve extends Method
{
    public function gdoParameters()
    {
        return array(
            GDT_Token::make('token'),
            GDT_Object::make('id')->table(GDO_GuestbookMessage::table())
        );
    }
    
    /**
     * @return GDO_GuestbookMessage
     */
    public function getMessage() { return $this->gdoParameterValue('id'); }
    public function getToken() { return $this->gdoParameterVar('token'); }
    
    public function execute()
    {
        $message = $this->getMessage();
        if ($this->getToken() !== $message->gdoHashcode())
        {
            return $this->error('err_token');
        }
        if ($message->isApproved())
        {
            return $this->error('err_already_approved');
        }
        
        return $this->approve($message);
    }
    
    public function approve(GDO_GuestbookMessage $message)
    {
        $message->saveVars(array(
            'gbm_approved' => Time::getDate(),
            'gbm_approved_by' => GDO_User::current()->getID(),
        ));
        
        $href = href('Guestbook', 'ApproveList', '&id='.$message->getGuestbookID());
        return $this->message('msg_gbm_approved')->addField(Website::redirect($href, 12));
    }

}
