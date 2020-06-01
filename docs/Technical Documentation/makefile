build = ./build
config = ../../phpdoc.dist.xml
phpdoc = phpDocumentor.phar

default : $(phpdoc) $(config)
	php $(phpdoc) -c $(config)

clean :
	rm -r $(build)
