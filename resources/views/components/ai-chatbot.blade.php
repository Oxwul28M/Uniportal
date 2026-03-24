{{--
AI Chatbot Component — Student Role
Floating Action Button + Chat Window
Conecta con la ruta: ai.chat (POST → JSON)
--}}
<div x-data="{
        open: false,
        sending: false,
        messages: [
            {
                role: 'assistant',
                text: '¡Hola, {{ Auth::user()->name ?? 'Estudiante' }}! 👋 Soy tu asistente académico con IA. Puedo ayudarte con tus notas, horarios, pagos y más. ¿En qué te ayudo hoy?',
                time: new Date().toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit' })
            }
        ],
        input: '',
        csrfToken: document.querySelector('meta[name=csrf-token]')?.content ?? '',

        async send() {
            const question = this.input.trim();
            if (!question || this.sending) return;

            // Append user bubble
            this.messages.push({
                role: 'user',
                text: question,
                time: new Date().toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit' })
            });
            this.input = '';
            this.sending = true;

            // Typing indicator placeholder
            this.messages.push({ role: 'typing', text: '', time: '' });

            await this.$nextTick();
            this.scrollToBottom();

            try {
                const res  = await fetch('{{ route('ai.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({ message: question }),
                });
                const data = await res.json();

                // Remove typing indicator
                this.messages = this.messages.filter(m => m.role !== 'typing');

                if (data.reply) {
                    this.messages.push({
                        role: 'assistant',
                        text: data.reply,
                        time: new Date().toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit' })
                    });
                } else {
                    this.messages.push({
                        role: 'assistant',
                        text: data.error ?? 'Hubo un error al procesar tu consulta. Inténtalo de nuevo.',
                        time: new Date().toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit' })
                    });
                }
            } catch (e) {
                this.messages = this.messages.filter(m => m.role !== 'typing');
                this.messages.push({
                    role: 'assistant',
                    text: 'No pude conectarme con el asistente. Verifica tu conexión.',
                    time: new Date().toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit' })
                });
            }

            this.sending = false;
            await this.$nextTick();
            this.scrollToBottom();
        },

        scrollToBottom() {
            const el = this.$refs.messages;
            if (el) el.scrollTop = el.scrollHeight;
        },

        handleKey(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.send();
            }
        }
    }" class="fixed bottom-6 right-6 z-[200] flex flex-col items-end gap-3" x-cloak>
    {{-- ── Chat Window ── --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="w-[90vw] sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col"
        style="height: 520px; max-height: calc(100vh - 100px);">
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-brand-800 to-blue-600 shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="size-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-lg">smart_toy</span>
                    </div>
                    <span
                        class="absolute -bottom-0.5 -right-0.5 size-2.5 bg-emerald-400 border-2 border-brand-800 rounded-full"></span>
                </div>
                <div>
                    <p class="text-white text-sm font-bold leading-tight">Asistente UniPortal</p>
                    <p class="text-blue-200 text-[10px] font-medium">IA Académica • En línea</p>
                </div>
            </div>
            <button @click="open = false"
                class="p-1.5 rounded-xl text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>

        {{-- Messages Area --}}
        <div x-ref="messages" class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-gray-50/50"
            style="scroll-behavior: smooth;">
            <template x-for="(msg, index) in messages" :key="index">
                <div>
                    {{-- Assistant / Typing bubble --}}
                    <div x-show="msg.role === 'assistant' || msg.role === 'typing'" class="flex items-end gap-2">
                        <div class="size-7 rounded-lg bg-brand-800 flex items-center justify-center shrink-0 mb-0.5">
                            <span class="material-symbols-outlined text-white text-sm">smart_toy</span>
                        </div>
                        <div class="max-w-[80%]">
                            {{-- Typing indicator --}}
                            <div x-show="msg.role === 'typing'"
                                class="bg-white border border-gray-200 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                                <div class="flex items-center gap-1 h-4">
                                    <span class="size-1.5 bg-gray-400 rounded-full animate-bounce"
                                        style="animation-delay: 0ms"></span>
                                    <span class="size-1.5 bg-gray-400 rounded-full animate-bounce"
                                        style="animation-delay: 150ms"></span>
                                    <span class="size-1.5 bg-gray-400 rounded-full animate-bounce"
                                        style="animation-delay: 300ms"></span>
                                </div>
                            </div>
                            {{-- Normal assistant message --}}
                            <div x-show="msg.role === 'assistant'"
                                class="bg-white border border-gray-200 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                                <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-wrap" x-text="msg.text">
                                </p>
                            </div>
                            <p x-show="msg.role === 'assistant'" class="text-[10px] text-gray-400 font-medium mt-1 ml-1"
                                x-text="msg.time"></p>
                        </div>
                    </div>

                    {{-- User bubble --}}
                    <div x-show="msg.role === 'user'" class="flex items-end justify-end gap-2">
                        <div class="max-w-[80%]">
                            <div
                                class="bg-gradient-to-br from-brand-700 to-blue-600 rounded-2xl rounded-br-sm px-4 py-3 shadow-sm">
                                <p class="text-sm text-white leading-relaxed whitespace-pre-wrap" x-text="msg.text"></p>
                            </div>
                            <p class="text-[10px] text-gray-400 font-medium mt-1 mr-1 text-right" x-text="msg.time"></p>
                        </div>
                        <div
                            class="size-7 rounded-lg bg-gray-200 flex items-center justify-center shrink-0 mb-0.5 overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'U') }}&background=1e3a8a&color=fff&size=28"
                                class="w-full h-full object-cover" alt="Tu avatar" />
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input Area --}}
        <div class="shrink-0 px-4 py-3 bg-white border-t border-gray-100">
            <div class="flex items-end gap-2">
                <textarea x-model="input" @keydown="handleKey($event)" :disabled="sending"
                    placeholder="Escribe tu pregunta..." rows="1"
                    class="flex-1 resize-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all placeholder-gray-400 disabled:opacity-60 max-h-24"
                    style="field-sizing: content;"></textarea>
                <button @click="send()" :disabled="sending || !input.trim()"
                    class="shrink-0 size-10 flex items-center justify-center bg-gradient-to-br from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 disabled:opacity-40 text-white rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 disabled:hover:translate-y-0">
                    <template x-if="sending">
                        <span class="material-symbols-outlined text-sm animate-spin">refresh</span>
                    </template>
                    <span class="material-symbols-outlined text-sm" x-show="!sending">send</span>
                </button>
            </div>
            <p class="text-[10px] text-gray-400 mt-1.5 text-center">Asistente Interno UniPortal • Enter para enviar</p>
        </div>
    </div>

    {{-- ── Floating Action Button ── --}}
    <button @click="open = !open; if(open) $nextTick(() => scrollToBottom())"
        class="relative size-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white shadow-xl hover:shadow-2xl transition-all hover:-translate-y-1 active:scale-95"
        :title="open ? 'Cerrar asistente' : 'Abrir asistente IA'">
        {{-- Pulse ring when closed --}}
        <span x-show="!open" class="absolute inset-0 rounded-2xl bg-brand-600 animate-ping opacity-20"></span>

        <span class="material-symbols-outlined text-2xl transition-transform duration-300"
            :class="{ 'rotate-90': open }">
            smart_toy
        </span>

        {{-- Unread badge --}}
        <span x-show="!open"
            class="absolute -top-1 -right-1 size-4 bg-emerald-400 text-[8px] font-bold text-white rounded-full flex items-center justify-center border-2 border-white shadow">IA</span>
    </button>
</div>