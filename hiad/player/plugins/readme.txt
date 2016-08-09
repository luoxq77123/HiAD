1.插件调用基本说明:
  应用插件,需要将播放器的plugin参数设为true,并提供js函数getPlugins来返回插件配置数组.
2.getPlugins方法示例,返回的是一个json数组
 function getPlugins(){
	return '[{"source":"AdPlugin.swf","rc":"1","blockLoading":"true","callback":"getadData","blockPlaying":"true","host":"http://adms.sobey.com/entry.php"}]';
}
3.基本参数说明
 source(string):插件地址
 blockLoading(boolean):是否阻止播放器的加载直到插件加载完成
 blockPlaying(boolean):是否阻止播放器播放视频直至插件加载完成  