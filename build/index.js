(()=>{"use strict";var e,r={650:()=>{const e=window.wp.blocks,r=window.wp.i18n,o=window.wp.blockEditor,t=window.ReactJSXRuntime,i=JSON.parse('{"UU":"create-block/codelight"}');(0,e.registerBlockType)(i.UU,{edit:function(){return(0,t.jsx)("p",{...(0,o.useBlockProps)(),children:(0,r.__)("Codelight – hello from the editor!","codelight")})},save:function(){return(0,t.jsx)("p",{...o.useBlockProps.save(),children:"Codelight – hello from the saved content!"})}})}},o={};function t(e){var i=o[e];if(void 0!==i)return i.exports;var n=o[e]={exports:{}};return r[e](n,n.exports,t),n.exports}t.m=r,e=[],t.O=(r,o,i,n)=>{if(!o){var l=1/0;for(d=0;d<e.length;d++){o=e[d][0],i=e[d][1],n=e[d][2];for(var c=!0,s=0;s<o.length;s++)(!1&n||l>=n)&&Object.keys(t.O).every((e=>t.O[e](o[s])))?o.splice(s--,1):(c=!1,n<l&&(l=n));if(c){e.splice(d--,1);var a=i();void 0!==a&&(r=a)}}return r}n=n||0;for(var d=e.length;d>0&&e[d-1][2]>n;d--)e[d]=e[d-1];e[d]=[o,i,n]},t.o=(e,r)=>Object.prototype.hasOwnProperty.call(e,r),(()=>{var e={57:0,350:0};t.O.j=r=>0===e[r];var r=(r,o)=>{var i,n,l=o[0],c=o[1],s=o[2],a=0;if(l.some((r=>0!==e[r]))){for(i in c)t.o(c,i)&&(t.m[i]=c[i]);if(s)var d=s(t)}for(r&&r(o);a<l.length;a++)n=l[a],t.o(e,n)&&e[n]&&e[n][0](),e[n]=0;return t.O(d)},o=self.webpackChunkcodelight=self.webpackChunkcodelight||[];o.forEach(r.bind(null,0)),o.push=r.bind(null,o.push.bind(o))})();var i=t.O(void 0,[350],(()=>t(650)));i=t.O(i)})();