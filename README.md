# Next SemVers

GitHub Action that output the next version for major, minor, and patch version based on the given semver version.

![Example output showing this action in action](images/output.png)

## Options

This action supports the following options.

### version

The version we want to have the next versions for.

* *Required*: `Yes`
* *Type*: `string`
* *Example*: `v1.2.3` or `1.2.3`

### strict

Strict version validation, when turned off, the version is suffixed with `.0` until it contains 3 x `.`.

* *Required*: `No`
* *Type*: `string`
* *Example*: `true` or `false`

## Output

This action output 6 slightly different outputs. A new major, minor, and patch version and a variant of those prefixed
with a `v`. For example when you input `1.2.3` it will give you the following outputs:

* `major`: `2.0.0`
* `minor`: `1.3.0`
* `patch`: `1.2.4`
* `v_major`: `v2.0.0`
* `v_minor`: `v1.3.0`
* `v_patch`: `v1.2.4`

## Example

The following example works together with the [`WyriHaximus/github-action-get-previous-tag`](https://github.com/marketplace/actions/get-latest-tag)
and [`WyriHaximus/github-action-create-milestone`](https://github.com/marketplace/actions/create-milestone) actions.
Where it uses the output from that action to supply a set of versions for the next action, which creates a new
milestone. (This snippet has been taken from the automatic code generation of [`wyrihaximus/fake-php-version`](https://github.com/wyrihaximus/php-fake-php-version/).)

```yaml
name: Generate
jobs:
  generate:
    steps:
      - uses: actions/checkout@v1
      - name: 'Get Previous tag'
        id: previoustag
        uses: "WyriHaximus/github-action-get-previous-tag@master"
        env:
          GITHUB_TOKEN: "${{ secrets.GITHUB_TOKEN }}"
      - name: 'Get next minor version'
        id: semvers
        uses: "WyriHaximus/github-action-next-semvers@v1"
        with:
          version: ${{ steps.previoustag.outputs.tag }}
      - name: 'Create new milestone'
        id: createmilestone
        uses: "WyriHaximus/github-action-create-milestone@master"
        with:
          title: ${{ steps.semvers.outputs.patch }}
        env:
          GITHUB_TOKEN: "${{ secrets.GITHUB_TOKEN }}"
```

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
