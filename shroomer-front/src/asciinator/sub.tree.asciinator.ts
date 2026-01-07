/// <reference lib="es2021" />

interface Template {
  [key: string]:string
}

class SubTreeAsciinator {
// TEMPLATE 0_5
  static template_0: Template = {
    boletus:
      '            \n' +
      '   _---_    \n' +
      '  \'-( )-\'   \n' +
      '<span class="text-green-800">__._</span>( )<span class="text-green-800">_.___</span>',

    amanita:
      '            \n' +
      '    _----_  \n' +
      '  :_.-||-._:\n' +
      '<span class="text-green-800">__._._</span>||<span class="text-green-800">__._</span>',

    cantharellus:
      '            \n' +
      '            \n' +
      '            \n' +
      '<span class="text-green-800">.__</span>Y<span class="text-green-800">.__</span>Y<span class="text-green-800">_</span>Y<span class="text-green-800">._</span>',

    xerocomus:
      '            \n' +
      '            \n' +
      '    .XM.    \n' +
      '<span class="text-green-800">.__._</span>)(<span class="text-green-800">._.__</span>',

    morchella:
      '            \n' +
      '            \n' +
      '     0      \n' +
      '<span class="text-green-800">__._</span>/_\\<span class="text-green-800">__.__</span>',

    trunk:
      '      <span class="text-amber-800">||</span>    \n' +
      '      <span class="text-amber-800">||</span>    \n' +
      '      <span class="text-amber-800">||</span>    \n' +
      '<span class="text-green-800">__.</span><span class="text-amber-800">/|||/\\</span><span class="text-green-800">.._</span>',

    default:
      '            \n' +
      '            \n' +
      '            \n' +
      '<span class="text-green-800">__.__.__.___</span>',
  }

  static template_5: Template = {
    boletus:
      '   _---_    \n' +
      ' /       \\  \n' +
      ':_.-( )-._: \n' +
      '<span class="text-green-800">_._</span>(   )<span class="text-green-800">_.__</span>',

    amanita:
      '    _---_   \n' +
      '  /  * *  \\ \n' +
      ' :_.-| |-._:\n' +
      '<span class="text-green-800">__._.</span>| |<span class="text-green-800">__._</span>',

    cantharellus:
      '            \n' +
      '            \n' +
      '  \\ / \\ / / \n' +
      '<span class="text-green-800"></span>Y<span class="text-green-800">__</span>Y<span class="text-green-800">.__</span>Y<span class="text-green-800">_</span>Y<span class="text-green-800">._</span>',

    pleurotus:
      '            \n' +
      '            \n' +
      '     P|P    \n' +
      '<span class="text-green-800">.__.__</span>Y<span class="text-green-800">__.__</span>',

    xerocomus:
      '            \n' +
      '     ...    \n' +
      '   .XXCMM.  \n' +
      '<span class="text-green-800">.__._</span>) (<span class="text-green-800">_.__</span>',

    morchella:
      '     0      \n' +
      '    000     \n' +
      '     0      \n' +
      '<span class="text-green-800">__._</span>/_\\<span class="text-green-800">__.__</span>',

    trunk:
      '     <span class="text-amber-800">|||</span>    \n' +
      '     <span class="text-amber-800">|||</span>    \n' +
      '     <span class="text-amber-800">|||</span>    \n' +
      '<span class="text-green-800">__.</span><span class="text-amber-800">/|||/\\</span><span class="text-green-800">.._</span>',

    default:
      '            \n' +
      '            \n' +
      '            \n' +
      '<span class="text-green-800">__.__.__.___</span>',
  }

  prepareTemplate(size: number, type: string): string
  {
    if(5 <= size) {
      return SubTreeAsciinator.template_5[type] ?? SubTreeAsciinator.template_5.default
    }

    return SubTreeAsciinator.template_0[type] ?? SubTreeAsciinator.template_0.default
  }
}

export default new SubTreeAsciinator()
