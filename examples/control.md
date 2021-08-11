### Report JSON Structure

Output example [here](https://raw.githubusercontent.com/oscarweb/file-reporter/main/examples/cache/control.json "Output Json")

| Attribute | Type      | Description                      |
|:----------|:----------|:---------------------------------|
| `created` | `int`     | `strtotime('now')`               |
| `updated` | `int`     | `strtotime('now')`               |
| `reports` | `array`   | List of reports created          |

######  &#8212; Property `reports` `array`
| Attribute | Type      | Description                      |
|:----------|:----------|:---------------------------------|
| `route`   | `string`  | Path of the file or directory.   |
| `name`    | `string`  | File name.                       |
| `json`    | `string`  | Path of the created file.        |
| `created` | `int`     | `strtotime('now')`               |
| `updated` | `int`     | `strtotime('now')`               |
| `count`   | `array`   | Number of Directories and Files. |

######  &#8212;&#8212; Property `count` `array`
| Attribute | Type      | Description                      |
|:----------|:----------|:---------------------------------|
| `dirs`    | `int`     | Number of existing directories.  |
| `files`   | `int`     | Number of existing files.        |

