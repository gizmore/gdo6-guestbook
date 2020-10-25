<?php
namespace GDO\Guestbook\Method;

use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\Guestbook\GDO_Guestbook;
use GDO\Guestbook\GDO_GuestbookMessage;
use GDO\DB\GDT_Object;
use GDO\Core\GDT_Response;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\User\PermissionException;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_Paragraph;
use GDO\Guestbook\Module_Guestbook;

final class View extends MethodQueryList
{
    /** @var $guestbook GDO_Guestbook **/
    private $guestbook;
    
    public function gdoParameters()
    {
        return array_merge(parent::gdoParameters(), array(
            GDT_Object::make('id')->table(GDO_Guestbook::table())->notNull()->initial('1'),
        ));
    }
    
    /**
     * @return GDO_Guestbook
     */
    public function getGuestbook() { return $this->gdoParameterValue('id'); }
//     public function getID() { return $this->gdoParameterVar('id'); }
    
    public function init()
    {
        if (!($this->guestbook = $this->getGuestbook()))
        {
            return $this->error('err_no_guestbook');
        }
        if (!$this->guestbook->canView(GDO_User::current()))
        {
            return $this->error('err_permission_read');
        }
    }
    
    public function gdoQuery()
    {
        return parent::gdoQuery()->where('gbm_guestbook=' . $this->guestbook->getID())->
        where('gbm_approved IS NOT NULL')->where('gbm_deleted IS NULL');
    }
    
    public function gdoTable()
    {
        return GDO_GuestbookMessage::table();
    }

    protected function setupTitle(GDT_List $list)
    {
        $list->title(t('list_view_guestbook', [$list->countItems()]));
    }
    
    public function execute()
    {
        $gb = $this->guestbook;
        $mod = Module_Guestbook::instance();
        
        $bar = $mod->guestbookViewBar($gb);

        $card = null;
        if ($this->getPage() === '1')
        {
            $card = GDT_Card::make('gbcard')->gdo($gb);
            $card->title($gb->displayTitle());
            $card->addField(GDT_Paragraph::make()->html($gb->displayDescription()));
        }
        
        return $bar->add(GDT_Response::makeWith($card))->add($this->renderPage());
    }
    
}
