<?php
namespace GDO\Guestbook;
/**
 * Install the site guestbook.
 * @author gizmore
 */
final class Install
{
	public static function onInstall()
	{
		if (!(GDO_Guestbook::forSite()))
		{
		    GDO_Guestbook::blank(array(
    			'gb_id' => '1',
    			'gb_title' => t('guestbook_default_title'),
		        'gb_descr' => t('guestbook_default_descr'),
		    ))->insert();
		}
	}
}
