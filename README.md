# Debugger for PHP

Simple and lightweight:
* [Variable dump](#variable-dumping)
* [Incident rendering](#incident-rendering) 

Both of these features are divided and can used separately one from another.

![image](https://user-images.githubusercontent.com/9035401/53689123-68658700-3d46-11e9-936c-3f9d5b0565ac.png)

Variable dumping
--------------------

A single function to print out any variable type in an developer-friendly way.

##### Features

* Includes the __file and line__ of calling.
* __Automatically__ outputs in __HTML__, __JSON__ or __plain__ text, based on the environment.
* Very visible HTML output.
* [Configurable](#configuration).

##### Examples

Direct calling of the method.
<pre>
// Will automatically show HTML, JSON or plain text format
VarDump::from($var);
</pre>

Creating a quick debug function.
<pre>
// The second parameter tells the debugger not to state this function as the dump file/line
function d($var) {
	VarDump::from($var, 1);
}

...

// Use it globaly
d('something');
</pre>

Incident rendering
--------------------

Will consider convert all errors to exceptions and render a page for any and every non handled exception.

##### Features

* Shows the exception and the __stack trace__ with __code snippets__.
* __Automatically__ outputs in __HTML__, __JSON__ or __plain__ text, based on the environment.
* Very visible HTML output.
* [Configurable](#configuration).

##### Examples

Simply register the IncidentHandler.
<pre>
IncidentHandler::register();
</pre>

Configuration
--------------------

Both parts share the same configuration that is statically set.
There are options that are used by only one part.

##### Context lines

Defines the number of lines around a line that caused an error.

Default: <code>8</code>

<pre>

# Setter
\Intellex\Debugger\Config::setContextLines(:int)

# Getter
\Intellex\Debugger\Config::getContextLines(): int
</pre>  

##### Max dump size

The maximum size of an variable to display, in bytes.
Anything bigger will be shown as HUGE.

Default: <code>2097152</code>

<pre>
# Setter
\Intellex\Debugger\Config::setMaxDumpSize(:int)

# Getter
\Intellex\Debugger\Config::getMaxDumpSize(): int
</pre>  

##### Width for plain

The plain format is  width of the box in the plain text format.

Default: <code>140</code>

<pre>
# Setter
\Intellex\Debugger\Config::setWidthForPlain(:int)

# Getter
\Intellex\Debugger\Config::getWidthForPlain(): int
</pre>  

##### Template

Force the template to use.

Default: auto detect from the environment

Possible values: <code>'html'</code>, <code>'json'</code> or <code>'plain'</code>

<pre>
# Setter
\Intellex\Debugger\Config::setWidthForPlain(:string)

# Getter
\Intellex\Debugger\Config::getWidthForPlain(): string 
</pre>  

TODO
--------------------
1. Tests for incident rendering. 
2. Define the error types to ignore (ie. deprecated and strict). 
3. Leverage JavaScript to show the HTML debug in a more practical way. 
4. Plain text box to better handle multiline strings. 
5. Be able to configure the color scheme for the outputs. 
6. Create more stylish exception page.
7. Support for logging.

Licence
--------------------
MIT License

Copyright (c) 2019 Intellex

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

Credits
--------------------
Script has been written by the [Intellex](https://intellex.rs/en) team.
