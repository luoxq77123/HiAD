1.������û���˵��:
  Ӧ�ò��,��Ҫ����������plugin������Ϊtrue,���ṩjs����getPlugins�����ز����������.
2.getPlugins����ʾ��,���ص���һ��json����
 function getPlugins(){
	return '[{"source":"AdPlugin.swf","rc":"1","blockLoading":"true","callback":"getadData","blockPlaying":"true","host":"http://adms.sobey.com/entry.php"}]';
}
3.��������˵��
 source(string):�����ַ
 blockLoading(boolean):�Ƿ���ֹ�������ļ���ֱ������������
 blockPlaying(boolean):�Ƿ���ֹ������������Ƶֱ������������  