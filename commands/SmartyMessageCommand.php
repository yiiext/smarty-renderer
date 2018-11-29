<?php
Yii::import('system.cli.commands.MessageCommand');

class SmartyMessageCommand extends MessageCommand
{

    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic smartymessage <config-file>

DESCRIPTION
  This command searches for messages to be translated in the specified
  source files and compiles them into PHP arrays as message source.

PARAMETERS
 * config-file: required, the path of the configuration file. You can find
   an example in framework/messages/config.php.

   The file can be placed anywhere and must be a valid PHP script which
   returns an array of name-value pairs. Each name-value pair represents
   a configuration option.

   The following options are available:

   - sourcePath: string, root directory of all source files.
   - messagePath: string, root directory containing message translations.
   - languages: array, list of language codes that the extracted messages
     should be translated to. For example, array('zh_cn','en_au').
   - fileTypes: array, a list of file extensions (e.g. 'php', 'xml', 'tpl').
     Only the files whose extension name can be found in this list
     will be processed. If empty, all files will be processed.
   - exclude: array, a list of directory and file exclusions. Each
     exclusion can be either a name or a path. If a file or directory name
     or path matches the exclusion, it will not be copied. For example,
     an exclusion of '.svn' will exclude all files and directories whose
     name is '.svn'. And an exclusion of '/a/b' will exclude file or
     directory 'sourcePath/a/b'.
   - translator: the name of the function for translating messages.
     Defaults to 'Yii::t'. This is used as a mark to find messages to be
     translated. Accepts both string for single function name or array for
     multiple function names.
   - overwrite: if message file must be overwritten with the merged messages.
   - removeOld: if message no longer needs translation it will be removed,
     instead of being enclosed between a pair of '@@' marks.
   - sort: sort messages by key when merging, regardless of their translation
     state (new, obsolete, translated.)

EOD;
    }

    /**
     * Execute the action.
     * @param array command line parameters specific for this command
     */
    public function run($args)
    {
        if (!isset($args[0]))
            $this->usageError('the configuration file is not specified.');
        if (!is_file($args[0]))
            $this->usageError("the configuration file {$args[0]} does not exist.");

        $config = require_once($args[0]);
        $translator = 'Yii::t';
        extract($config);

        if (!isset($sourcePath, $messagePath, $languages))
            $this->usageError('The configuration file must specify "sourcePath", "messagePath" and "languages".');
        if (!is_dir($sourcePath))
            $this->usageError("The source path $sourcePath is not a valid directory.");
        if (!is_dir($messagePath))
            $this->usageError("The message path $messagePath is not a valid directory.");
        if (empty($languages))
            $this->usageError("Languages cannot be empty.");

        if (!isset($overwrite))
            $overwrite = false;

        if (!isset($removeOld))
            $removeOld = false;

        if (!isset($sort))
            $sort = false;

        $options = array();
        if (isset($fileTypes))
            $options['fileTypes'] = $fileTypes;
        if (isset($exclude))
            $options['exclude'] = $exclude;
        $files = CFileHelper::findFiles(realpath($sourcePath), $options);

        $messages = array();
        foreach ($files as $file) {
            $ext = strtolower(CFileHelper::getExtension($file));

            $extractMessages = $this->extractMessages($file, $translator);
            if ($ext == 'tpl') {
                $extractMessages = array_merge_recursive($extractMessages, $this->extractMessagesSmartySyntax($file));
            }

            $messages = array_merge_recursive($messages, $extractMessages);
        }
        foreach ($languages as $language) {
            $dir = $messagePath . DIRECTORY_SEPARATOR . $language;
            if (!is_dir($dir))
                @mkdir($dir);
            foreach ($messages as $category => $msgs) {
                $msgs = array_values(array_unique($msgs));
                $this->generateMessageFile($msgs, $dir . DIRECTORY_SEPARATOR . $category . '.php', $overwrite, $removeOld, $sort);
            }
        }
    }

    protected function extractMessagesSmartySyntax($fileName)
    {
        echo "Extracting messages from $fileName...\n";
        $subject = file_get_contents($fileName);
        $messages = array();

        $regexp_tag = '~\{t(\s*[\w]+=((\'|")+(.*?(?<!\\))\3)|\$[\w]+|\[[^\]]*\]))*\s*\}~s';
        $regexp_cat = '~cat=[\'"]+(.*?)[\'"]+(?=\}+|\s+)~si';
        $regexp_text = '~text=(\'|")+(.*?(?<!\\\))\1(?=\}+|\s+)~si';

        if (preg_match_all($regexp_tag, $subject, $match)) {
            foreach($match[0] AS $value){
                $cat = '';
                $text = '';

                if(preg_match($regexp_cat, $value, $match)){
                    $cat = $match[1];
                    if (($pos = strpos($cat, '.')) !== false) {
                        $cat = substr($cat, $pos + 1);
                    }
                }
                if(preg_match($regexp_text, $value, $match)){
                    $text = $match[2];
                }
                if($cat && $text){
                    $messages[$cat][] = $text;
                }
            }
        }
        return $messages;
    }
}