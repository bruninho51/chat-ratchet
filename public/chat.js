var app = new Vue({
   el: '#app',
   data: {
       user: 'Anônimo',
       text: null,
       messages: [],
       ws: null
   },
   created: function () {
       this.connect();
   },
   methods: {
       connect: function (onOpen) {
           var self = this;

           self.ws = new WebSocket('ws://localhost:8080');
           self.ws.onopen = function () {
               self.addSuccessNotification('Conectado');

               if (onOpen) {
                   onOpen();
               }
           };

           self.ws.onerror = function () {
               self.addErrorNotification('Não foi possível conectar-se ao servidor!');
           };

           self.ws.onmessage = function (e) {
               self.addMessage(JSON.parse(e.data));
           };
       },
       addMessage: function (data) {
           this.messages.push(data);
           this.scrollDown();
       },
       addSuccessNotification: function (text) {
           this.addMessage({ color: 'green', text: text });
       },
       addErrorNotification: function (text) {
           this.addMessage({ color: 'red', text: text });
       },
       sendMessage: function () {
           var self = this;

           if (!self.text || !self.user) {
               return;
           }

           if (self.ws.readyState !== self.ws.OPEN) {
               self.addErrorNotification('Problemas na conexão. Tentando reconectar...');

               self.connect(function () {
                  self.sendMessage();
               });

               return;
           }

           self.ws.send(JSON.stringify({
               user: self.user,
               text: self.text
           }));

           self.text = null;
       },
       scrollDown: function () {
           var container = this.$el.querySelector('#messages');
           container.scrollTop = container.scrollHeight;
       }
   }
});