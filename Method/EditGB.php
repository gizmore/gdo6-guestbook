<?php
namespace GDO\Guestbook\Method;

final class Guestbook_EditGB extends GWF_Method
{
	public function isLoginRequired() { return true; }
	
	public function execute()
	{
		if (false === ($gb = GWF_Guestbook::getByID(Common::getGet('gb')))) {
			return $this->module->error('err_gb');
		}
		
		if (false === $gb->canModerate(GWF_Session::getUser())) {
			return GWF_HTML::err('ERR_NO_PERMISSION');
		}
		
		return $this->templateModerate();
	}

	public function templateModerate()
	{
		
	}
	
}

?>