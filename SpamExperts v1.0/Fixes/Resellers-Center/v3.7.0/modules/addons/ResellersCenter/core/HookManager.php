<?php
namespace MGModule\ResellersCenter\core;

use MGModule\ResellersCenter\Addon;

/**
 * Description of HookManager
 *
 * @author Paweł Złamaniec <pawel.zl@modulesgarden.com>
 */
class HookManager 
{
    const HOOKS_DIR = __DIR__ . DS . "hooks";
    const HOOKS_NAMESPACE = '\MGModule\ResellersCenter\core\hooks\\';
    
    //Classes name only - not objects!!
    private $hooks = array();
    
    /**
     * Load Hooks
     * 
     * @since 3.0.0
     */
    public function __construct() 
    {
        $this->hooks = $this->getHooksClasses();
        $this->registerHooks();
    }

//** start Qedit
	
	/** 
	 * Edit by Arjen Kocken, QMSPS, v210515.01
	 * 
     * Problem: Error when loading SpamExperts addon in WHMCS admin area, when ResellerCenter is activated
	 * Fixed: Disable loading Resellers Center hooks on specified URL in admin area
     * 
	 **/
  
	$url = "https://[YOUR_WHMCS_URL]/admin/addonmodules.php?module=spamexperts*";
	
	// Q: Start if else statement 
	if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
    
	// Q: Load SpamExperts and stop loading ResellerCenter directory scan for hooks
	
	} else {
    
	// Q: Start original code: ResellersCenter directory scanning for hooks to load ResellersCenter
	
	 /**
     * Scan directory in order to find hook classes
     * 
     * @since 3.0.0
     */
    
	public function getHooksClasses()
    {
        $files = glob(self::HOOKS_DIR.DS."*.php");
        $hooks = array_diff($files, array ('.', '..'));      
        
        foreach($hooks as &$hook)
        {
            $hook = basename(substr($hook, 0, -4));
        }
        
        return $hooks;
    }
	
	// Q: Stop original code
	
	}	// Q: Closing if else statement
	
//** stopQedit**/
	
    /**
     * Scan directory in order to find hook classes
     * 
     * @since 3.0.0
     */
    public function getHooksClasses()
    {
        $files = glob(self::HOOKS_DIR.DS."*.php");
        $hooks = array_diff($files, array ('.', '..'));      
        
        foreach($hooks as &$hook)
        {
            $hook = basename(substr($hook, 0, -4));
        }
        
        return $hooks;
    }
    
    /**
     * Register hooks in WHMCS system
     * 
     * @since 3.0.0
     */
    public function registerHooks()
    {
        if(!Addon::I()->configuration()->hooksEnabled)
        {
            return;
        }

        foreach($this->hooks as $hook)
        {
            require self::HOOKS_DIR.DS.$hook.".php";
            
            $classname = self::HOOKS_NAMESPACE.$hook;
            $obj = new $classname();

            /* Preventing foreach PHP warnings */
            if(!is_array($obj->functions) && !is_object($obj->functions))
            {
                continue;
            }

            foreach ($obj->functions as $priority => $func) {
                add_hook($hook, $priority, $func);
            }

        }
    }
}
