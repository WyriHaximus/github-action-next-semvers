# github-action-next-semvers

Github Action that output the next version for major, minor, and patch version based on the given semver version.

![Example output showing this action in action](images/output.png)

## Options

This action supports the following options.

### version

The version we want to have the next versions for.

* *Required*: `Yes`
* *Type*: `string`
* *Example*: `v1.2.3` or `1.2.3`

## Output

This action output 6 slightly different outputs. A new major, minor, and patch version and a variant of those prefixed 
with a `v`. For example when you input `1.2.3` it will give you the following outputs:

* `major`: `2.0.0`
* `minor`: `1.3.0`
* `patch`: `1.2.4`
* `v_major`: `v2.0.0`
* `v_minor`: `v1.3.0`
* `v_patch`: `v1.2.4`

## License ##

Copyright 2019 [Cees-Jan Kiewiet](http://wyrihaximus.net/)

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
