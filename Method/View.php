<?php
namespace GDO\Guestbook\Method;

use GDO\Table\MethodQueryList;
use GDO\Guestbook\GDO_Guestbook;
use GDO\Guestbook\GDO_GuestbookMessage;
use GDO\DB\GDT_Object;
use GDO\Core\GDT_Response;
use GDO\User\GDO_User;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_Paragraph;
use GDO\Guestbook\Module_Guestbook;
use GDO\Table\GDT_Table;

final class View extends MethodQueryList
{
    /** @var $guestbook GDO_Guestbook **/
    private $guestbook;

    public function getDefaultOrder() { return 'gbm_created'; }
    public function getDefaultOrderDir() { return false; }
    
    public function gdoParameters()
    {
        return array_merge(parent::gdoParameters(), array(
            GDT_Object::make('id')->table(GDO_Guestbook::table())->notNull()->initial('1')->searchable(false)->orderable(false),
        ));
    }
    
    public function gdoHeaders()
    {
        $table = GDO_GuestbookMessage::table();
        return array(
            $table->gdoColumn('gbm_message'),
            $table->gdoColumn('gbm_user'),
            $table->gdoColumn('gbm_email'),
            $table->gdoColumn('gbm_website'),
            $table->gdoColumn('gbm_created'),
        );
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
    
    public function getQuery()
    {
        return $this->gdoTable()->select('gdo_guestbookmessage.*')->
            where('gbm_guestbook=' . $this->guestbook->getID())->
            where('gbm_approved IS NOT NULL')->
            where('gbm_deleted IS NULL')->
            joinObject('gbm_user');
    }
    
    public function gdoTable()
    {
        return GDO_GuestbookMessage::table();
    }

    protected function setupTitle(GDT_Table $list)
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
            $card->title($gb->gdoColumn('gb_title'));
            if ($gb->getID() !== '1')
            {
                $card->creatorHeader(null, 'gb_uid');
            }
            $card->addField($gb->gdoColumn('gb_descr'));
        }
        
        return $bar->add(GDT_Response::makeWith($card))->add($this->renderTable());
    }
    
}
