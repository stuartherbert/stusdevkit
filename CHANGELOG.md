# CHANGELOG

## Introduction

This is the CHANGELOG for [Stus's Dev Kit](https://github.com/stuartherbert/stusdevkit).

- This project uses [semantic versioning](./docs/01-Engineering-Standards/Versioning.md).
- This project uses [conventional commits](./docs/01-Engineering-Standards/Git-Commit-Messages.md).

## Auto-Built

This CHANGELOG is automatically compiled.

- run `make init` to setup the `git` hooks to auto-update this CHANGELOG.
- run `make changelog` to manually update this CHANGELOG.


## Upcoming Release

### Features


#### MissingBitsKit

- introduce JsonValidationError ([77ee86e](https://github.com/stuartherbert/stusdevkit/commit/77ee86e)) — Stuart Herbert <stuart@stuartherbert.com>
- added ClassInstantiability enum ([a8ea4d4](https://github.com/stuartherbert/stusdevkit/commit/a8ea4d4)) — Stuart Herbert <stuart@stuartherbert.com>
- added EnumToArray ([80d2ca6](https://github.com/stuartherbert/stusdevkit/commit/80d2ca6)) — Stuart Herbert <stuart@stuartherbert.com>
- added StaticallyArrayable interface ([eb01012](https://github.com/stuartherbert/stusdevkit/commit/eb01012)) — Stuart Herbert <stuart@stuartherbert.com>
- added FlattenClassTypes ([74c552e](https://github.com/stuartherbert/stusdevkit/commit/74c552e)) — Stuart Herbert <stuart@stuartherbert.com>
- added initial set of TypeInspector classes ([78d13cc](https://github.com/stuartherbert/stusdevkit/commit/78d13cc)) — Stuart Herbert <stuart@stuartherbert.com>
- added get_class_basename() ([2014612](https://github.com/stuartherbert/stusdevkit/commit/2014612)) — Stuart Herbert <stuart@stuartherbert.com>
- added uri_resolve_reference ([fdbc892](https://github.com/stuartherbert/stusdevkit/commit/fdbc892)) — Stuart Herbert <stuart@stuartherbert.com>
- added `object_merge` function ([4d69919](https://github.com/stuartherbert/stusdevkit/commit/4d69919)) — Stuart Herbert <stuart@stuartherbert.com>

#### DependencyKit

- added ResolveParameters tool ([f3d3f8e](https://github.com/stuartherbert/stusdevkit/commit/f3d3f8e)) — Stuart Herbert <stuart@stuartherbert.com>
- added ResolveParameter ([375d39a](https://github.com/stuartherbert/stusdevkit/commit/375d39a)) — Stuart Herbert <stuart@stuartherbert.com>
- added UntypedParameterException ([9d747f9](https://github.com/stuartherbert/stusdevkit/commit/9d747f9)) — Stuart Herbert <stuart@stuartherbert.com>
- added UnsupportedParameterTypeException ([fa422f1](https://github.com/stuartherbert/stusdevkit/commit/fa422f1)) — Stuart Herbert <stuart@stuartherbert.com>
- added UnresolvedParameterException ([21a2077](https://github.com/stuartherbert/stusdevkit/commit/21a2077)) — Stuart Herbert <stuart@stuartherbert.com>
- added DependencyNotFoundException ([29d4ce5](https://github.com/stuartherbert/stusdevkit/commit/29d4ce5)) — Stuart Herbert <stuart@stuartherbert.com>
- add PSR container package ([2d399b9](https://github.com/stuartherbert/stusdevkit/commit/2d399b9)) — Stuart Herbert <stuart@stuartherbert.com>
- added skeleton ([fd014db](https://github.com/stuartherbert/stusdevkit/commit/fd014db)) — Stuart Herbert <stuart@stuartherbert.com>

#### ExceptionsKit

- added InvalidMethodException ([ef0015a](https://github.com/stuartherbert/stusdevkit/commit/ef0015a)) — Stuart Herbert <stuart@stuartherbert.com>
- added InvalidFunctionException ([11519c7](https://github.com/stuartherbert/stusdevkit/commit/11519c7)) — Stuart Herbert <stuart@stuartherbert.com>
- added InvalidClassException ([3574803](https://github.com/stuartherbert/stusdevkit/commit/3574803)) — Stuart Herbert <stuart@stuartherbert.com>
- added InvalidArgumentException ([88fc3e7](https://github.com/stuartherbert/stusdevkit/commit/88fc3e7)) — Stuart Herbert <stuart@stuartherbert.com>

#### MissingKitsBit

- initial set of reflection tools ([b88fc35](https://github.com/stuartherbert/stusdevkit/commit/b88fc35)) — Stuart Herbert <stuart@stuartherbert.com>

#### ValidationKit

- initial support for external $ref loading ([247f4e4](https://github.com/stuartherbert/stusdevkit/commit/247f4e4)) — Stuart Herbert <stuart@stuartherbert.com>
- add support for $anchor in any sub-schema ([c59f26a](https://github.com/stuartherbert/stusdevkit/commit/c59f26a)) — Stuart Herbert <stuart@stuartherbert.com>
- schema $id is now exported to JSON schema ([ba71e0a](https://github.com/stuartherbert/stusdevkit/commit/ba71e0a)) — Stuart Herbert <stuart@stuartherbert.com>
- add further JSON schema constraints ([3f35f37](https://github.com/stuartherbert/stusdevkit/commit/3f35f37)) — Stuart Herbert <stuart@stuartherbert.com>
- add support for resolving references during JSON schema import ([ce6db71](https://github.com/stuartherbert/stusdevkit/commit/ce6db71)) — Stuart Herbert <stuart@stuartherbert.com>
- upgrade JsonSchemaRegistry capabilities ([61a7de2](https://github.com/stuartherbert/stusdevkit/commit/61a7de2)) — Stuart Herbert <stuart@stuartherbert.com>
- added JsonSchemaLoader ([726a4f3](https://github.com/stuartherbert/stusdevkit/commit/726a4f3)) — Stuart Herbert <stuart@stuartherbert.com>
- add support for `contentEncoding`, `contentMediaType` and `contentSchema` ([69ed395](https://github.com/stuartherbert/stusdevkit/commit/69ed395)) — Stuart Herbert <stuart@stuartherbert.com>
- add support for unevaluatedProperties and unevaluatedItems ([0191c78](https://github.com/stuartherbert/stusdevkit/commit/0191c78)) — Stuart Herbert <stuart@stuartherbert.com>
- add a bunch more JSON Schema string formats ([0111ce5](https://github.com/stuartherbert/stusdevkit/commit/0111ce5)) — Stuart Herbert <stuart@stuartherbert.com>
- support JsonSchema `$comment` ([333df90](https://github.com/stuartherbert/stusdevkit/commit/333df90)) — Stuart Herbert <stuart@stuartherbert.com>
- add Validation::string()->dateTime() format support ([ad044c4](https://github.com/stuartherbert/stusdevkit/commit/ad044c4)) — Stuart Herbert <stuart@stuartherbert.com>
- added Validate::assocArray(); Validate::object() now works with actual objects ([6318797](https://github.com/stuartherbert/stusdevkit/commit/6318797)) — Stuart Herbert <stuart@stuartherbert.com>
- initial support for creating a validator from JSON schema ([2cc32b7](https://github.com/stuartherbert/stusdevkit/commit/2cc32b7)) — Stuart Herbert <stuart@stuartherbert.com>
- initial support for JSON schema export ([ca1257e](https://github.com/stuartherbert/stusdevkit/commit/ca1257e)) — Stuart Herbert <stuart@stuartherbert.com>
- added Validate::lazy() ([b477726](https://github.com/stuartherbert/stusdevkit/commit/b477726)) — Stuart Herbert <stuart@stuartherbert.com>
- added ValidationSchema::withTransformer() ([22149b6](https://github.com/stuartherbert/stusdevkit/commit/22149b6)) — Stuart Herbert <stuart@stuartherbert.com>
- introduce ValidationSchema interface ([8e69224](https://github.com/stuartherbert/stusdevkit/commit/8e69224)) — Stuart Herbert <stuart@stuartherbert.com>
- initial support for codecs ([31e7fb9](https://github.com/stuartherbert/stusdevkit/commit/31e7fb9)) — Stuart Herbert <stuart@stuartherbert.com>
- added Validate::uuid() ([5981663](https://github.com/stuartherbert/stusdevkit/commit/5981663)) — Stuart Herbert <stuart@stuartherbert.com>
- added Validate::when() ([a4e5e02](https://github.com/stuartherbert/stusdevkit/commit/a4e5e02)) — Stuart Herbert <stuart@stuartherbert.com>
- added missing JSON schema logic/sub-schema support ([5950061](https://github.com/stuartherbert/stusdevkit/commit/5950061)) — Stuart Herbert <stuart@stuartherbert.com>
- add Validate::nullish() ([f3e7be5](https://github.com/stuartherbert/stusdevkit/commit/f3e7be5)) — Stuart Herbert <stuart@stuartherbert.com>
- initial code commit ([319c6f6](https://github.com/stuartherbert/stusdevkit/commit/319c6f6)) — Stuart Herbert <stuart@stuartherbert.com>
- added placeholder ([0dc3ba3](https://github.com/stuartherbert/stusdevkit/commit/0dc3ba3)) — Stuart Herbert <stuart@stuartherbert.com>

#### CollectionsKit

- added CollectionAsStack and StackOfStrings ([23db293](https://github.com/stuartherbert/stusdevkit/commit/23db293)) — Stuart Herbert <stuart@stuartherbert.com>

#### AssertionsKit

- initial build of PHPUnit assertions API ([b9e9711](https://github.com/stuartherbert/stusdevkit/commit/b9e9711)) — Stuart Herbert <stuart@stuartherbert.com>

#### changelog-tool

- added initial changelog-tool ([9ec9113](https://github.com/stuartherbert/stusdevkit/commit/9ec9113)) — Stuart Herbert <stuart@stuartherbert.com>

### Bug Fixes


#### MissingBitsKit

- only GetDuckTypes now returns `mixed` ([04c1916](https://github.com/stuartherbert/stusdevkit/commit/04c1916)) — Stuart Herbert <stuart@stuartherbert.com>
- FlattenReflectionType now throws if asked to flatten an intersection type ([da90743](https://github.com/stuartherbert/stusdevkit/commit/da90743)) — Stuart Herbert <stuart@stuartherbert.com>

#### ValidationKit

- improve round-trip JSON Schema import and export ([c14fa32](https://github.com/stuartherbert/stusdevkit/commit/c14fa32)) — Stuart Herbert <stuart@stuartherbert.com>
- $ref handling now matches JSON Schema Draft 2020-12 behaviour ([d45f497](https://github.com/stuartherbert/stusdevkit/commit/d45f497)) — Stuart Herbert <stuart@stuartherbert.com>
- PHP associative array schemas can now be exported as JSON schema ([7c0bd28](https://github.com/stuartherbert/stusdevkit/commit/7c0bd28)) — Stuart Herbert <stuart@stuartherbert.com>
- add support for JsonSchema type being an array of types (needed for OpenAPI v3.1 support) ([b599573](https://github.com/stuartherbert/stusdevkit/commit/b599573)) — Stuart Herbert <stuart@stuartherbert.com>
- remove all mention of PipelineStep from the public API ([00392f4](https://github.com/stuartherbert/stusdevkit/commit/00392f4)) — Stuart Herbert <stuart@stuartherbert.com>
- Validate::uuid() now follows same principles as the other schemas ([fa8af3c](https://github.com/stuartherbert/stusdevkit/commit/fa8af3c)) — Stuart Herbert <stuart@stuartherbert.com>
- make it easier for third-party validators to create validation issues ([5a7b06d](https://github.com/stuartherbert/stusdevkit/commit/5a7b06d)) — Stuart Herbert <stuart@stuartherbert.com>
- Validate::discriminatedAnyof() performance improvement ([204b3af](https://github.com/stuartherbert/stusdevkit/commit/204b3af)) — Stuart Herbert <stuart@stuartherbert.com>

#### CollectionsKit

- StackOfStrings::toArray() now returns the internal data in LIFO order ([dd13227](https://github.com/stuartherbert/stusdevkit/commit/dd13227)) — Stuart Herbert <stuart@stuartherbert.com>

#### Tools

- fix `make claudecodekit` ([e80e5c3](https://github.com/stuartherbert/stusdevkit/commit/e80e5c3)) — Stuart Herbert <stuart@stuartherbert.com>

#### AssertionsKit

- Assert implementation now uses docblocks from parent interface ([b6d7ba1](https://github.com/stuartherbert/stusdevkit/commit/b6d7ba1)) — Stuart Herbert <stuart@stuartherbert.com>

### Documentation

- improve README for AssertionsKit ([38713af](https://github.com/stuartherbert/stusdevkit/commit/38713af)) — Stuart Herbert <stuart@stuartherbert.com>
- move CHANGELOG preamble into separate files, to improve readability ([53fdfb6](https://github.com/stuartherbert/stusdevkit/commit/53fdfb6)) — Stuart Herbert <stuart@stuartherbert.com>
- added initial README.md ([054e3bc](https://github.com/stuartherbert/stusdevkit/commit/054e3bc)) — Stuart Herbert <stuart@stuartherbert.com>
- explain phpstan template limitations on affected collection classes ([9604c11](https://github.com/stuartherbert/stusdevkit/commit/9604c11)) — Stuart Herbert <stuart@stuartherbert.com>

#### DependencyKit

- generate manual page for ResolveParameter ([64e4a39](https://github.com/stuartherbert/stusdevkit/commit/64e4a39)) — Stuart Herbert <stuart@stuartherbert.com>
- experimenting with AI-generated docs ([024e8e3](https://github.com/stuartherbert/stusdevkit/commit/024e8e3)) — Stuart Herbert <stuart@stuartherbert.com>

#### Engineering Standards

- update note about Arrayable ([3f33016](https://github.com/stuartherbert/stusdevkit/commit/3f33016)) — Stuart Herbert <stuart@stuartherbert.com>
- initial commit ([f53ef84](https://github.com/stuartherbert/stusdevkit/commit/f53ef84)) — Stuart Herbert <stuart@stuartherbert.com>

#### ResolveParameters

- improve docblocks ([6d21048](https://github.com/stuartherbert/stusdevkit/commit/6d21048)) — Stuart Herbert <stuart@stuartherbert.com>

#### ValidationKit

- make README more useful as a reference ([6521431](https://github.com/stuartherbert/stusdevkit/commit/6521431)) — Stuart Herbert <stuart@stuartherbert.com>
- explain the individual steps of a validator ([8cb5ec5](https://github.com/stuartherbert/stusdevkit/commit/8cb5ec5)) — Stuart Herbert <stuart@stuartherbert.com>
- added initial README ([421b82b](https://github.com/stuartherbert/stusdevkit/commit/421b82b)) — Stuart Herbert <stuart@stuartherbert.com>

#### AssertionKit

- add list of available methods ([7962911](https://github.com/stuartherbert/stusdevkit/commit/7962911)) — Stuart Herbert <stuart@stuartherbert.com>

### Refactoring

- NullValueNotAllowedException is now part of the ExceptionsKit ([db9e182](https://github.com/stuartherbert/stusdevkit/commit/db9e182)) — Stuart Herbert <stuart@stuartherbert.com>

#### MissingBitsKit

- move Json into its own namespace ([27a402a](https://github.com/stuartherbert/stusdevkit/commit/27a402a)) — Stuart Herbert <stuart@stuartherbert.com>

#### CollectionsKit

- moved Arrayable into MissingBitsKit ([f80dc1c](https://github.com/stuartherbert/stusdevkit/commit/f80dc1c)) — Stuart Herbert <stuart@stuartherbert.com>
- introduce AccessibleCollection intermediate class ([565e5fe](https://github.com/stuartherbert/stusdevkit/commit/565e5fe)) — Stuart Herbert <stuart@stuartherbert.com>

#### ValidationKit

- improve usability of ErrorFormatter ([6b049d7](https://github.com/stuartherbert/stusdevkit/commit/6b049d7)) — Stuart Herbert <stuart@stuartherbert.com>
- more work to make custom validators easier to create ([3b43d4d](https://github.com/stuartherbert/stusdevkit/commit/3b43d4d)) — Stuart Herbert <stuart@stuartherbert.com>
- existing transformers now use BaseTransformer to simplify implementation ([0ede17e](https://github.com/stuartherbert/stusdevkit/commit/0ede17e)) — Stuart Herbert <stuart@stuartherbert.com>
- built-in constraints now use the BaseConstraint to simplify the code ([cf0172b](https://github.com/stuartherbert/stusdevkit/commit/cf0172b)) — Stuart Herbert <stuart@stuartherbert.com>
- introduce BaseConstraint and BaseTransformer to simplify custom validator classes ([50c5b16](https://github.com/stuartherbert/stusdevkit/commit/50c5b16)) — Stuart Herbert <stuart@stuartherbert.com>
- now requires the PHP intl extension ([10d40a0](https://github.com/stuartherbert/stusdevkit/commit/10d40a0)) — Stuart Herbert <stuart@stuartherbert.com>
- move the JsonSchema support into a dedicated namespace ([33a9afb](https://github.com/stuartherbert/stusdevkit/commit/33a9afb)) — Stuart Herbert <stuart@stuartherbert.com>
- Validate::array()->nonempty() is now Validate::array->notEmpty() ([4fc09ab](https://github.com/stuartherbert/stusdevkit/commit/4fc09ab)) — Stuart Herbert <stuart@stuartherbert.com>
- tidy up ValidationSchema metadata API ([c5d6a97](https://github.com/stuartherbert/stusdevkit/commit/c5d6a97)) — Stuart Herbert <stuart@stuartherbert.com>
- absorb the traits into the BaseSchema ([cc7551c](https://github.com/stuartherbert/stusdevkit/commit/cc7551c)) — Stuart Herbert <stuart@stuartherbert.com>
- improve naming of custom constraints and custom transforms ([ea941f6](https://github.com/stuartherbert/stusdevkit/commit/ea941f6)) — Stuart Herbert <stuart@stuartherbert.com>
- remove `withStep()` from public API ([61b9ce1](https://github.com/stuartherbert/stusdevkit/commit/61b9ce1)) — Stuart Herbert <stuart@stuartherbert.com>
- standardise naming of public API methods ([3f491d8](https://github.com/stuartherbert/stusdevkit/commit/3f491d8)) — Stuart Herbert <stuart@stuartherbert.com>
- tidy up default error reporting ([9580045](https://github.com/stuartherbert/stusdevkit/commit/9580045)) — Stuart Herbert <stuart@stuartherbert.com>
- reduce number of schemas that need unique parsing pipelines ([54d4bc3](https://github.com/stuartherbert/stusdevkit/commit/54d4bc3)) — Stuart Herbert <stuart@stuartherbert.com>
- simplify transformers implementation ([db1e258](https://github.com/stuartherbert/stusdevkit/commit/db1e258)) — Stuart Herbert <stuart@stuartherbert.com>
- tidy up implementation of type coercion ([b5b86d2](https://github.com/stuartherbert/stusdevkit/commit/b5b86d2)) — Stuart Herbert <stuart@stuartherbert.com>
- CoerceToDateTime is now able to accept a larger number of datetime formats ([3aa1d54](https://github.com/stuartherbert/stusdevkit/commit/3aa1d54)) — Stuart Herbert <stuart@stuartherbert.com>
- Validate::boolean()->coerce() can now replace the default list of supported strings ([0939e7a](https://github.com/stuartherbert/stusdevkit/commit/0939e7a)) — Stuart Herbert <stuart@stuartherbert.com>
- improve implementation of CoerceToBoolean ([0e79a0f](https://github.com/stuartherbert/stusdevkit/commit/0e79a0f)) — Stuart Herbert <stuart@stuartherbert.com>
- extract coercion code into separate classes ([ee29081](https://github.com/stuartherbert/stusdevkit/commit/ee29081)) — Stuart Herbert <stuart@stuartherbert.com>
- issues are now returned as a ValidationIssuesList ([7f5ccf4](https://github.com/stuartherbert/stusdevkit/commit/7f5ccf4)) — Stuart Herbert <stuart@stuartherbert.com>
- Validate::allOf() now takes a list of schemas ([909fadc](https://github.com/stuartherbert/stusdevkit/commit/909fadc)) — Stuart Herbert <stuart@stuartherbert.com>
- Validate::allOf() now takes a list of schemas ([d64c31d](https://github.com/stuartherbert/stusdevkit/commit/d64c31d)) — Stuart Herbert <stuart@stuartherbert.com>
- rename some schemas to be closer to JSON schema design ([8fb7e88](https://github.com/stuartherbert/stusdevkit/commit/8fb7e88)) — Stuart Herbert <stuart@stuartherbert.com>
- handle optional and nullable better ([ad2edc7](https://github.com/stuartherbert/stusdevkit/commit/ad2edc7)) — Stuart Herbert <stuart@stuartherbert.com>
- remove duplicate code ([10c3cae](https://github.com/stuartherbert/stusdevkit/commit/10c3cae)) — Stuart Herbert <stuart@stuartherbert.com>
- make schemas extensible via withConstraint() method ([03adc7d](https://github.com/stuartherbert/stusdevkit/commit/03adc7d)) — Stuart Herbert <stuart@stuartherbert.com>
- move default typeCheckError callback definitions into methods, which can be overridden by child classes if required ([06406c5](https://github.com/stuartherbert/stusdevkit/commit/06406c5)) — Stuart Herbert <stuart@stuartherbert.com>
- DateTimeSchema is now DateTimeInterfaceSchema ([5207876](https://github.com/stuartherbert/stusdevkit/commit/5207876)) — Stuart Herbert <stuart@stuartherbert.com>
- move validators for built-in PHP object types into their own namespace ([86941c7](https://github.com/stuartherbert/stusdevkit/commit/86941c7)) — Stuart Herbert <stuart@stuartherbert.com>
- bring PHP builtins validators into a common namespace ([863da42](https://github.com/stuartherbert/stusdevkit/commit/863da42)) — Stuart Herbert <stuart@stuartherbert.com>

#### ValidatorKit

- drop ValidatorSchema readonly concept ([10cdbcc](https://github.com/stuartherbert/stusdevkit/commit/10cdbcc)) — Stuart Herbert <stuart@stuartherbert.com>

#### ValidationLib

- simplify processing pipeline ([d5cecd0](https://github.com/stuartherbert/stusdevkit/commit/d5cecd0)) — Stuart Herbert <stuart@stuartherbert.com>

#### AssertionsKit

- Assert interface is now AssertApi, to reduce confusion ([2a160b7](https://github.com/stuartherbert/stusdevkit/commit/2a160b7)) — Stuart Herbert <stuart@stuartherbert.com>
- drop `assertThat()` - not really a public assert API ([ecda25e](https://github.com/stuartherbert/stusdevkit/commit/ecda25e)) — Stuart Herbert <stuart@stuartherbert.com>
- now throws InvalidArgumentException where appopriate ([525b7b1](https://github.com/stuartherbert/stusdevkit/commit/525b7b1)) — Stuart Herbert <stuart@stuartherbert.com>
