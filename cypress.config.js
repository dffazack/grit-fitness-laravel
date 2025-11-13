import { defineConfig } from "cypress";
import { exec } from "child_process";
import http from "http";
import url from "url";

// A simple server to render Blade components via PHP
const bladeDevServer = (on, config) => {
  return new Promise((resolve) => {
    const server = http.createServer((req, res) => {
      const { pathname } = url.parse(req.url);
      if (pathname === '/render') {
        let body = '';
        req.on('data', chunk => {
          body += chunk.toString();
        });
        req.on('end', () => {
          // Execute the PHP script to render the Blade component
          const php = exec(`php cypress/support/render-blade.php`, (error, stdout, stderr) => {
            if (error) {
              console.error(`exec error: ${error}`);
              res.writeHead(500);
              res.end(stderr);
              return;
            }
            res.writeHead(200, { 'Content-Type': 'text/html' });
            res.end(stdout);
          });
          // Pass the blade string to the PHP script via stdin
          php.stdin.write(body);
          php.stdin.end();
        });
      } else {
        res.writeHead(404);
        res.end();
      }
    });

    server.listen(0, '127.0.0.1', () => {
      const { port } = server.address();
      console.log(`Blade component server listening on port ${port}`);
      resolve({ port });
    });
  });
};

export default defineConfig({
  e2e: {
    baseUrl: "http://127.0.0.1:8000",
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
  component: {
    devServer: bladeDevServer,
    specPattern: "cypress/component/**/*.cy.js",
  },
});