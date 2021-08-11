#### Report JSON Structure

Output example [here](https://raw.githubusercontent.com/oscarweb/file-reporter/main/examples/cache/report.15ae3f61f9f41738356cee922fe2e655.json "Output Json")

| Attribute | Type      | Description                      |
|:----------|:----------|:---------------------------------|
| `route`   | `string`  | Directory path                   |
| `name`    | `string`  | Directory name                   |
| `created` | `integer` | `strtotime('now')`               |
| `updated` | `integer` | `strtotime('now')`               |
| `count`   | `array`   | Number of Directories and Files. |
| `content` | `array`   | List of files and directories.   |

######  &#8212; Property `count` `array`
| Attribute | Type      | Description                      |
|:----------|:----------|:---------------------------------|
| `dirs`    | `int`     | Number of existing directories.  |
| `files`   | `int`     | Number of existing files.        |

######  &#8212; Property `content` `array`
| Attribute | Type      | Description                                 |
|:----------|:----------|:--------------------------------------------|
| `type`    | `string`  | Determines the type of the element          |
| `is`      | `array`   | Returns boolean values for `dir` and `file` |
| `data`    | `array`   | Records file or directory information.      |

######  &#8212;&#8212; Property `is` `array`
| Attribute | Type      | Description                                 |
|:----------|:----------|:--------------------------------------------|
| `dir`     | `bool`    | Determine if it is a directory.             |
| `file`    | `bool`    | Determine if it is a file.                  |

######  &#8212;&#8212; Property `data` `array`
| Attribute | Type      | Description                                 |
|:----------|:----------|:--------------------------------------------|
| `mime`    | `string`  | Returns the MIME content type.              |
| `ext`     | `string`  | Returns the file extension.                 |
| `name`    | `string`  | File name.                                  |
| `mtime`   | `int`     | `strtotime('now')`.                         |
| `route`   | `string`  | Path of the file or directory.              |
| `hash`    | `string`  | SHA-1 hash file.                            |

