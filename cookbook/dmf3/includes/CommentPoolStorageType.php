<?php if (!defined('PmWiki')) exit();
abstract class CommentPoolStorageType extends BasicEnum {
    const Cached = "CommentPoolStorageType_Cached";
    const PmWiki = "CommentPoolStorageType_PmWiki";
    const SQLite = "CommentPoolStorageType_SQLite";
}